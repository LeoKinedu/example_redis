<?php

namespace App\Providers\CustomProviders;

use Illuminate\Queue\Failed\DynamoDbFailedJobProvider;
use Illuminate\Queue\Failed\FailedJobProviderInterface;
use Illuminate\Support\ServiceProvider;

class CustomDynamodbFailedJobProvider extends DynamoDbFailedJobProvider
{

    public function __construct($resolver, $database, $table)
    {
        parent::__construct($resolver, $database, $table);
    }

    public function log($connection, $queue, $payload, $exception)
    {
        $objPayload = json_decode($payload);

        $jobClass = collect(config('queue.ignore_jobs', []))->first(function($value, $key) use ($objPayload){
            return $value === $objPayload->displayName;
        });

        if ($jobClass) {
            \Log::error("--Logic Failed : {$objPayload->displayName}--");
            return 0;
        }
        return parent::log($connection, $queue, $payload, $exception);
    }
}
