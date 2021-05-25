<?php

namespace App\Providers;

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
        // Get a default implementation to trigger a deferred binding
        $_ = $this->app['queue.failer'];

        // Swap the implementation
        $this->app->singleton('queue.failer', function ($app) {
        $config = $app['config']['queue.failed'];

        return new AppServiceProviderOverride();

        });
    }
}
