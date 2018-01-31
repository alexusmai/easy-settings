<?php

namespace Alexusmai\EasySettings\Facades;

use Illuminate\Support\Facades\Facade;

class EasySettingsFacade extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'easy-settings';
    }
}