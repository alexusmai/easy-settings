<?php

namespace Alexusmai\EasySettings;

use Log;
use Cache;
Use Exception;
use Alexusmai\EasySettings\Models\EasySettings as EasySettingsModel;

class EasySettings
{
    /**
     * @var EasySettingsModel
     */
    private $model;

    /**
     * EasySettings constructor.
     *
     * @param EasySettingsModel $model
     */
    public function __construct(EasySettingsModel $model)
    {
        $this->model = $model;
    }

    /**
     * Get settings
     *
     * @param      $path
     * @param null $default
     *
     * @return null
     */
    public function get($path, $default = null)
    {

        try {
            // prepare settings path
            list($groupName, $settingsName) = $this->preparePath($path);

            $item = $this->getGroupFromDB($groupName);

            // if not found
            if (!$item) {
                throw new Exception("Settings group ".$groupName." not found!");
            }

            // if array is empty
            if (!$item->data) {
                throw new Exception('Not found any data for this group! '
                    .$groupName);
            }

            $data = array_dot($item->data);

            // find field type
            $fieldType = $this->fieldType($item->schema, $settingsName);

            // for radios type
            if ($fieldType === 'radios') {

                // if this key doesn't exist
                if (!array_key_exists($settingsName, $data)) {
                    throw new Exception('Settings name not found! '.$groupName
                        .'.'.$settingsName);
                }

                // return boolean type
                return $data[$settingsName] === 'true';

            } elseif ($fieldType === 'lang') {

                // get locale
                $locale = \App::getLocale();

                if (array_key_exists($settingsName.'.'.$locale, $data)) {
                    return $data[$settingsName.'.'.$locale];
                }

                // if a default variable set
                if ($default) {
                    return $default;
                }

                // default lang name
                $settingsName .= '.'.config('app.fallback_locale');
            }

            // if this key doesn't exist
            if (!array_key_exists($settingsName, $data)) {
                throw new Exception('Settings name not found! '.$groupName.'.'
                    .$settingsName);
            }

            return $data[$settingsName];

        } catch (Exception $e) {
            // log error message
            Log::error($e->getMessage());

            return $default;
        }

    }

    /**
     * @param $key
     *
     * @return array
     * @throws Exception
     */
    protected function preparePath($key)
    {
        $array = explode('.', $key);

        if (count($array) < 2) {
            throw new Exception('Invalid key - '.$key);
        }

        return [array_shift($array), implode('.', $array)];
    }

    /**
     * Get settings group
     *
     * @param $groupName
     *
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    protected function getGroupFromDB($groupName)
    {
        // Cache
        if (config('easy-settings.cache')) {
            $item = Cache::remember('esettings-'.$groupName,
                config('easy-settings.cache'), function () use ($groupName) {
                    return $this->model->where('name', $groupName)->first();
                });
        } else {
            // get settings from db
            $item = $this->model->where('name', $groupName)->first();
        }

        return $item;
    }

    /**
     * Get field type
     *
     * @param $schema
     * @param $settingsName
     *
     * @return mixed
     */
    protected function fieldType($schema, $settingsName)
    {
        $index = array_search($settingsName, array_column($schema, 'name'));

        return $schema[$index]['type'];
    }
}
