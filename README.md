# Easy settings - Laravel + Vue package

[![Latest Stable Version](https://poser.pugx.org/alexusmai/easy-settings/v/stable)](https://packagist.org/packages/alexusmai/easy-settings)
[![Total Downloads](https://poser.pugx.org/alexusmai/easy-settings/downloads)](https://packagist.org/packages/alexusmai/easy-settings)
[![Latest Unstable Version](https://poser.pugx.org/alexusmai/easy-settings/v/unstable)](https://packagist.org/packages/alexusmai/easy-settings)
[![License](https://poser.pugx.org/alexusmai/easy-settings/license)](https://packagist.org/packages/alexusmai/easy-settings)

![Easy Settings Vue App](https://raw.github.com/alexusmai/laravel-vue-easy-settings/master/src/assets/esettings.gif?raw=true)

The application is designed for convenient and fast work with simple data of your application.

For example, you need to place a phone number in the footer of your site.
You can enter it directly into the HTML code, but if you need to change it, you'll have to dig into the code.
And if you make a website to a client, then he will not even know how to change this number.
Of course you can write your own CRUD's for all items ... or you can just use this package.

This package is suitable for storing such data as:
- strings
- numbers, phone numbers,
- small blocks of text
- Boolean types (true, false) ON / OFF

Also, if you have a multilingual site, then you will also be able to make your work easier, and save the required data in several languages.

## Installation

Composer

``` bash
composer require alexusmai/easy-settings
```

If you have Laravel 5.4 or earlier version, then add service provider to config/app.php and

``` php
Alexusmai\EasySettings\EasySettingsServiceProvider::class,
```

add alias.

``` php
'ESettings' => Alexusmai\EasySettings\Facades\EasySettingsFacade::class,
```

Publish config file (easy-settings.php)

``` bash
php artisan vendor:publish --tag=easy-settings-config
```

You can install npm package directly and use it in your vue application - more information about it -
[laravel-vue-easy-settings](https://github.com/alexusmai/laravel-vue-easy-settings)

OR

Publish js and css files - laravel-vue-easy-settings package (compiled, minimised)

``` bash
php artisan vendor:publish --tag=easy-settings-assets
```

Run migration -> this command create "easy-settings" table

```php
php artisan migrate
```

## Settings

Open configuration file - config/easy-settings.php
``` php
/**
     * List of languages
     * add the necessary ones to create additional fields(field type "Lang")
     */
    'languages'     => ['en', 'ru'],

    /**
     * Development mode
     * Show - edit/add settings group
     */
    'dev'           => true,

    /**
     * Save data to laravel cache
     * The cache will not work in development mode
     * set null, 0 - if you don't need cache (default)
     * if you want use cache - set the number of minutes for which the value should be cached
     */
    'cache'         => null,

    /**
     * Middleware
     * Add your middleware name to array -> ['web', 'auth', 'admin']
     * !!!! RESTRICT ACCESS FOR NON ADMIN USERS !!!!
     */
    'middleware'    => ['web', 'auth']
```

In '`languages`' array you can add the necessary languages

To create and edit groups of settings, you must use the `developer mode`.
After you have added the necessary groups of settings, disable the developer mode if you do not want users to be able to change the structure, validation rules, delete, etc.

`Use caching, this will avoid unnecessary queries to the database`

**Be sure to add your middleware to restrict access to the application**

###Open the view file where you want to place the application block, and add:

- add a csrf token to head block if you did not do it before
```html
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```
- the package uses some styles of Bootstrap 4, if you already use it, then you do not need to connect any styles.
 Otherwise add -

```html
<link href="{{ asset('vendor/easy-settings/css/esettings.css') }}" rel="stylesheet">
```

- add js (laravel-vue-easy-settings)
```html
<script src="{{ asset('vendor/easy-settings/js/esettings.js') }}"></script>
```

- add div for application
```html
<div id="easy-settings-app"></div>
```

## Usage

Now it remains to add the necessary settings groups, add fields, validation rules and can be used in your code.

```php
ESettings::get('groupName.settingsName');
ESettings::get('groupName.settingsName', $defaultValue);
```

- If you have chosen 'radios' type, the result will be a boolean type (true or false).
- If "Lang" type is selected, the result will depend on the settings of the language of your application, at the time of the call.
- If the desired language is not found - will be use fallback locale (see config/app.php) or default value.
```php
'fallback_locale' => 'en',
```
