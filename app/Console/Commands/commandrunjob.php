<?php

namespace App\Console\Commands;

use App\Jobs\SendMsnJob;
use Illuminate\Console\Command;

class CommandRunJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:jobmsn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SendMsnJob::dispatch("titulo del job");
    }

}
