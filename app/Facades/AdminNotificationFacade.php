<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AdminNotificationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'adminnotification';
    }
}