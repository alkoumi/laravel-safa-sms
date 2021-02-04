<?php

namespace Alkoumi\LaravelSafaSms\Facades;

use Illuminate\Support\Facades\Facade;

class SafaSMS extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'SafaSMS';
    }
}
