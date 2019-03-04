<?php

namespace Alexusmai\EasySettings;

use Alexusmai\EasySettings\Commands\EasySettingsCreateSeed;
use Illuminate\Support\ServiceProvider;
use Alexusmai\EasySettings\Models\EasySettings as EasySettingsModel;

class EasySettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // commands
        $this->commands(EasySettingsCreateSeed::class);

        // routes
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        // migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // language files
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'esettings');

        // publish config
        $this->publishes([
            __DIR__
            .'/../config/easy-settings.php' => config_path('easy-settings.php'),
        ], 'easy-settings-config');

        // publish language files
        $this->publishes([
            __DIR__
            .'/../resources/lang' => resource_path('lang/vendor/easy-settings'),
        ], 'easy-settings-lang');

        // publish js and css files - vue-easy-settings module
        $this->publishes([
            __DIR__
            .'/../resources/assets' => public_path('vendor/easy-settings'),
        ], 'easy-settings-assets');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('easy-settings', function () {
            return new EasySettings(new EasySettingsModel);
        });
    }
}
