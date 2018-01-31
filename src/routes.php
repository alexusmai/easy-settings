<?php

Route::group([
    'middleware'    => config('easy-settings.middleware'),
    'prefix'        => 'easy-settings',
    'namespace'     => 'Alexusmai\EasySettings\Controllers'
], function (){

    Route::get('initiate', 'SettingsController@initiate')->name('settings.initiate');

    Route::get('list', 'SettingsController@settingsList')->name('settings.list');

    Route::post('add-group', 'SettingsController@addGroup')->name('settings.addGroup');

    Route::post('update-group', 'SettingsController@updateGroup')->name('settings.updateGroup');

    Route::get('delete-group/{id}', 'SettingsController@deleteGroup')->name('settings.deleteGroup')
        ->where('id', '[0-9]+');

    Route::post('set-settings', 'SettingsController@setSettings')->name('settings.setSettings');
});