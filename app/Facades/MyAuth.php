<?php

namespace App\Facades;

use Illuminate\Support\Facades\Auth;

class MyAuth extends Auth
{
    public static function getGuardCurrent()
    {
        $guardName = self::getDefaultDriver();

        return config('auth.guards')[$guardName];
    }

    public static function getProviderCurrent()
    {
        $providerName = self::getGuardCurrent()['provider'];

        return config('auth.providers')[$providerName];
    }

    public static function getModelFromProvider()
    {
        $provider = self::getProviderCurrent();
        $model = $provider['model'];

        return app("\\{$model}");
    }
}
