<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class QueueServiceOverrideProvider extends ServiceProvider
{

    public function boot()
    {
        // Get a default implementation to trigger a deferred binding
        $_ = $this->app['queue.failer'];

        // Swap the implementation
        $this->app->singleton('queue.failer', function ($app) {
            $config = $app['config']['queue.failed'];

            return new AppServiceProviderOverride(
                $this->app['db'], $config['database'], $config['table']
            );
        });
    }

}
