<?php

namespace Alexusmai\EasySettings;

use Cache;
use Alexusmai\EasySettings\Models\EasySettings as EasySettingsModel;

class EasySettings
{
    /**
     * @var EasySettingsModel
     */
    private $model;

    /**
     * EasySettings constructor.
     * @param EasySettingsModel $model
     */
    public function __construct(EasySettingsModel $model)
    {
        $this->model = $model;
    }

    /**
     * Get settings
     * @param $path
     * @param null $default
     * @return null
     */
    public function get($path, $default = null)
    {
        // prepare settings path
        $params = $this->preparePath($path);
        $groupName = $params['groupName'];
        $settingsName = $params['settingsName'];

        // Cache
        if (config('easy-settings.cache') && !config('easy-settings.dev')) {
            $item = Cache::remember('esettings-'.$groupName, config('easy-settings.cache'), function () use ($groupName) {
                return $this->model->where('name', $groupName)->first();
            });
        } else {
            // get settings from db
            $item = $this->model->where('name', $groupName)->first();
        }

        // if not found
        if (!$item) return $default;

        // if array is empty
        if (!$item->data) return $default;

        $data = array_dot($item->data);

        // find field type
        $fieldType = $this->fieldType($item->schema, $settingsName);

        // for radios type
        if ($fieldType === 'radios') {

            // if this key doesn't exist
            if ( !array_key_exists($settingsName, $data)) return $default;

            // return boolean type
            return $data[$settingsName] === 'true';

        } elseif ($fieldType === 'lang') {
            // for lang type fields

            // get locale
            $locale = \App::getLocale();

            if ( array_key_exists($settingsName.'.'.$locale, $data)) {
                return $data[$settingsName.'.'.$locale];
            }

            // if a default variable set
            if ($default) return $default;

            // default lang name
            $settingsName .= '.'.config('app.fallback_locale');
        }

        // if this key doesn't exist
        if ( !array_key_exists($settingsName, $data)) return $default;

        return $data[$settingsName];
    }

    /**
     * @param string $key
     * @return array
     */
    protected function preparePath($key)
    {
        $array = explode('.', $key);

        if (count($array) < 2) trigger_error("Invalid key", E_USER_ERROR);

        return [
            'groupName'     => array_shift($array),
            'settingsName'  => implode('.',$array)
        ];
    }

    /**
     * Get field type
     * @param $schema
     * @param $settingsName
     * @return mixed
     */
    protected function fieldType($schema, $settingsName)
    {
        $index = array_search($settingsName, array_column($schema, 'name'));
        return $schema[$index]['type'];
    }
}