<?php

namespace App\Providers;

use Illuminate\Queue\Failed\DatabaseUuidFailedJobProvider;
use Illuminate\Queue\Failed\FailedJobProviderInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProviderOverride extends DatabaseUuidFailedJobProvider
{

    public function __construct($resolver, $database, $table)
    {
        parent::__construct($resolver, $database, $table);
    }

    public function log($connection, $queue, $payload, $exception)
    {
        $objPayload = json_decode($payload);

        if ($objPayload->displayName == "App\Jobs\SendMsnJob") {
            \Log::error("--Logic Failed : {$objPayload->displayName}--");
            return 0;
        }
        return parent::log($connection, $queue, $payload, $exception);
    }
}
