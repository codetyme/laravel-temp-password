<?php
namespace Codetyme\TempPassword\Facades;

use Illuminate\Support\Facades\Facade;

class TempPass extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'temp-password';
    }
}
