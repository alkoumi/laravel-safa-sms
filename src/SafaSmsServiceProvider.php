<?php

namespace Alkoumi\LaravelSafaSms;

use Alkoumi\LaravelSafaSms\Facades\SafaSMS;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;


class SafaSmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        AliasLoader::getInstance()->alias('SafaSMS', SafaSMS::class);
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $config = __DIR__ . '/../config/safa-sms.php';
        $this->mergeConfigFrom($config, 'safa-sms');
        $this->publishes([__DIR__ . '/../config/safa-sms.php' => config_path('safa-sms.php')], 'config');


        $this->app->singleton('SafaSMS', function () {
            return $this->app->make('Alkoumi\LaravelSafaSms\SafaSMS');
        });

    }
}
