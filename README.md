# Please don't use now !!!! TEST !!!
# Easy settings - Laravel + Vue package

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

You can install npm package directly and use it in your vue application - more informations about it you can find here

OR

Publish js and css files - laravel-vue-easy-settings package (compiled, minimised)

``` bash
php artisan vendor:publish --tag=easy-settings-assets
```

## Usage



If the desired language is not found - will be use fallback locale
     * config/app.php --->  falback_locale