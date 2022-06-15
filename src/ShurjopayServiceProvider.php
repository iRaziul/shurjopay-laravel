<?php

namespace Raziul\Shurjopay;

use Illuminate\Support\ServiceProvider;

class ShurjopayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/shurjopay.php', 'shurjopay');

        $this->app->singleton(Gateway::class, function ($app) {
            return new Gateway($app['config']['shurjopay']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/shurjopay.php' => config_path('shurjopay.php'),
            ], 'shurjopay');
        }
    }
}
