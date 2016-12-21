<?php

namespace LaraPolices\Providers;

use Illuminate\Support\ServiceProvider;

class PolicesServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     */
    public function boot()
    {
    }

    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '../config/polices.php' => config_path('polices.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '../config/polices.php',
            'polices'
        );
    }
}
