<?php

namespace Alexusmai\EasySettings\Controllers;

use Alexusmai\EasySettings\Models\EasySettings;
use Alexusmai\EasySettings\Requests\GroupSchemaRequest;
use Alexusmai\EasySettings\Traits\SchemaService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Cache;

class SettingsController extends Controller
{
    use SchemaService;

    /**
     * @var EasySettings
     */
    protected $model;

    /**
     * SettingsController constructor.
     *
     * @param EasySettings $settings
     */
    public function __construct(EasySettings $settings)
    {
        $this->model = $settings;
    }

    /**
     * Initial settings for vue app
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function initiate()
    {
        $locale = \App::getLocale();

        return response()->json([
            'config' => config('easy-settings'),
            'locale' => $locale,
        ]);
    }

    /**
     * Settings list
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function settingsList()
    {
        return $this->model->all();
    }

    /**
     * Add new group
     *
     * @param GroupSchemaRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addGroup(GroupSchemaRequest $request)
    {
        // names must be unique
        if (count($request->input('name'))
            !== count(array_unique($request->input('name')))
        ) {
            return response()->json(
                ['errors' => [trans('esettings::response.uniqueNames')]],
                422
            );
        }

        $schema = $this->createSchemaArray($request->except('id'));

        $newGroup = new EasySettings();
        $newGroup->name = $request->input('groupName');
        $newGroup->title = $request->input('groupTitle');
        $newGroup->schema = $schema;
        $newGroup->save();

        return response()->json(['message' => trans('esettings::response.groupAdded')]);
    }

    /**
     * Update selected settings group
     *
     * @param GroupSchemaRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateGroup(GroupSchemaRequest $request)
    {
        // names must be unique
        if (count($request->input('name'))
            !== count(array_unique($request->input('name')))
        ) {
            return response()->json(
                ['errors' => [trans('esettings::response.uniqueNames')]],
                422
            );
        }

        $schema = $this->createSchemaArray($request->except('id'));

        $settingsGroup = $this->model->where('id', $request->input('id'))
            ->firstOrFail();
        $settingsGroup->title = $request->input('groupTitle');
        $settingsGroup->schema = $schema;
        $settingsGroup->save();

        return response()->json(['message' => trans('esettings::response.groupUpdated')]);
    }

    /**
     * Delete group
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteGroup($id)
    {
        $item = $this->model->findOrFail($id);
        $item->delete();

        return response()->json(['message' => trans('esettings::response.groupDeleted')]);
    }


    /**
     * Update settings
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setSettings(Request $request)
    {
        $group = $this->model->findOrFail($request->input('easy-settings-id'));

        // create rules array
        $rules = $this->createRulesArray($group);

        // validate data
        $this->validate($request, $rules);

        // save new data to DB
        $group->data = $request->except(['easy-settings-id', '_token']);
        $group->save();

        // clear cache
        if (config('easy-settings.cache')) {
            Cache::forget('esettings-'.$group->name);
        }

        return response()->json(['message' => trans('esettings::response.settingsUpdated')]);
    }

}
