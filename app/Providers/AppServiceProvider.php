<?php

namespace App\Providers;

use collect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('settings')) {
            $this->loadConfig();
        }
    }

    public function loadConfig()
    {
        config([
            'mail.mailers.smtp' => $this->mergeConfig(setting('mail', []), config('mail.mailers.smtp')),
            'mail.from' => $this->mergeConfig(setting('mail', []), config('mail.from')),
            'mp' => $this->mergeConfig(setting('mp', []), config('mp')),
            'googlemaps.key' => collect($this->mergeConfig(
                ['key' => setting('google.maps_api_key')],
                config('googlemaps'))
            )->get('key'),
            'googlerecaptchav3.secret_key' => collect($this->mergeConfig(
                ['secret_key' => setting('google.recaptcha_private_key')],
                config('googlerecaptchav3')
            ))->get('secret_key'),
            'googlerecaptchav3.site_key' => collect($this->mergeConfig(
                ['site_key' => setting('google.recaptcha_public_key')],
                config('googlerecaptchav3')
            ))->get('site_key'),
        ]);
    }

    /**
     * Mescla as configurações do banco com as configurações do .env.
     *
     * @param array $config
     * @param array $configDefault
     * @return array
     */
    public function mergeConfig(array $config, array $configDefault)
    {
        // remove empty keys
        $config = array_filter($config);
        // get only keys exists in default configuration
        $config = collect($config)->intersectByKeys($configDefault)->toArray();

        return array_replace($configDefault, $config);
    }
}
