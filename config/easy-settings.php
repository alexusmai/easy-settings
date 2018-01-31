<?php
/**
 * Easy-settings config
 */
return [

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
];