<?php

namespace Attla\Disposable\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Validator;
use Attla\Disposable\Rules\DisposableEmail as ValidatorRule;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->configPath() => config_path('disposable.php'),
        ], 'attla/disposable/config');

        Validator::extend('disposable', ValidatorRule::class . '@passes');
    }

    /**
     * Register the application services
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            $this->configPath(),
            'disposable'
        );
    }

    /**
     * Get config path
     *
     * @param bool
     */
    protected function configPath()
    {
        return dirname(__DIR__) . '/../config/disposable.php';
    }
}
