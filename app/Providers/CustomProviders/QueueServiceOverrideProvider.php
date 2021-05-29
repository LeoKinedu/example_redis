<?php

namespace App\Providers\CustomProviders;

use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Failed\NullFailedJobProvider;
use \App\Providers\CustomProviders\CustomDatabaseUuidFailedJobProvider;
use \App\Providers\CustomProviders\CustomDynamodbFailedJobProvider;
use \App\Providers\CustomProviders\CustomFailedJobFailedJobProvider;

class QueueServiceOverrideProvider extends ServiceProvider
{

    public function boot()
    {
        // Get a default implementation to trigger a deferred binding
        $_ = $this->app['queue.failer'];

        // Swap the implementation
        $this->app->singleton('queue.failer', function ($app) {
            $config = $app['config']['queue.failed'];
            if(!isset($config['driver'])){
                return new NullFailedJobProvider;
            }
            $driver = $config['driver'];

            if($driver === 'dynamodb'){
                return new CustomDynamodbFailedJobProvider(
                    $this->app['db'],
                    $config['database'],
                    $config['table']);
            }
            if($driver === 'database-uuids'){
                return new CustomDatabaseUuidFailedJobProvider(
                    $this->app['db'],
                    $config['database'],
                    $config['table']);
            }
            if($driver['table']){
                return new CustomFailedJobFailedJobProvider(
                    $this->app['db'],
                    $config['database'],
                    $config['table']);
            }
        });
    }

}
