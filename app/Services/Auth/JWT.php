<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Facade;
use Ramsey\Collection\Map\AssociativeArrayMap;

class JWT extends Facade{
    
    /**
     *  @method static AssociativeArrayMap auth(AssociativeArrayMap $amap)
     *  @see TgJwtService
     */

     /**
      *  @method static string generate(array $header, array $payload, string $secret, int $validity = 86400)
      * @see TgJwtService
      */
    protected static function getFacadeAccessor()
    {
        return TgJwtService::class;
    }

}