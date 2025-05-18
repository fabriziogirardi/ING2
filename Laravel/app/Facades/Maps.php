<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Maps extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Maps';
    }
}
