<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JobExample2 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $title = "";
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title)
    {
        $this->onConnection('redis_example');
        $this->title = $title;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->fail();

    }

    public function failed(\Exception $exception = null)
    {
        \Log::error("Método failed JobExample 2 ");
    }
}
