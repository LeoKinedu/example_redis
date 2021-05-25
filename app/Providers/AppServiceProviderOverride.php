<?php

namespace App\Providers;

use Illuminate\Queue\Failed\FailedJobProviderInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProviderOverride implements FailedJobProviderInterface
{

    public function log($connection, $queue, $payload, $exception)
    {$objPayload = json_decode($payload);

        if($objPayload->displayName == "App\Jobs\SendMsnJob"){
            \Log::error("--Logic Failed : {$objPayload->displayName}--");
            return 0;
        }
        return 0;


    }

    /**
     * Get a list of all of the failed jobs.
     *
     * @return array
     */
    public function all()
    {
        return $this->getAllJobs();
    }

    /**
     * Get a single failed job.
     *
     * @param mixed $id
     *
     * @return object|null
     */
    public function find($id)
    {
        return $this->getJob((int) $id);
    }

    /**
     * Delete a single failed job from storage.
     *
     * @param mixed $id
     *
     * @return bool
     */
    public function forget($id)
    {
        return $this->deleteJob((int) $id);
    }

    /**
     * Flush all of the failed jobs from storage.
     *
     * @return void
     */
    public function flush()
    {
        $this->deleteAllJobs();
    }

    /**
     * Returns a new id.
     *
     * @return int
     */
    protected function getNewId()
    {
        $f = $this->getSequenceFilename();

        file_put_contents($f, $newId = (int) @file_get_contents($f) + 1, LOCK_EX);

        return $newId;
    }

    /**
     * Returns the absolute path to the sequence file.
     *
     * @return string
     */
    private function getSequenceFilename()
    {
    }

    /**
     * Traverses failed jobs storage and execute a callable
     * for each entry (file).
     *
     * @param \Closure $callable
     */
    protected function traverseStorage(\Closure $callable)
    {

    }

    /**
     * Returns all failed jobs.
     *
     * @return array
     */
    protected function getAllJobs()
    {
        $all = [];



        return array_values($all);
    }

    /**
     * Returns a failed job given the id.
     *
     * @param $jobId
     *
     * @return null
     */
    protected function getJob($jobId)
    {
        $job = null;

        $this->traverseStorage(function ($filename, $connection, $queue) use ($jobId, &$job) {
            list($id, $ts) = explode('_', basename($filename));

            if ($jobId == (int) $id) {
                $job = $this->getJobFromFile($filename, $connection, $queue);

                return false;
            }

            return true;
        });

        return $job;
    }

    /**
     * Delete a failed job given the id.
     *
     * @param $jobId
     *
     * @return bool
     */
    protected function deleteJob($jobId)
    {
        $success = false;

        $this->traverseStorage(function ($filename, $connection, $queue) use ($jobId, &$success) {
            list($id, $ts) = explode('_', basename($filename));

            if ($jobId == (int) $id) {
                $success = @unlink($filename);

                return false;
            }

            return true;
        });

        return $success;
    }

    /**
     * Delete all failed jobs.
     */
    protected function deleteAllJobs()
    {
        $this->traverseStorage(function ($filename, $connection, $queue) {
            @unlink($filename);

            return true;
        });
    }

    /**
     * Retrieve a failed job from the file.
     *
     * @param $filename
     * @param $connection
     * @param $queue
     *
     * @return object
     */
    protected function getJobFromFile($filename, $connection, $queue)
    {
        list($id, $ts) = explode('_', basename($filename));

        return (object) [
            'id'         => (int) $id,
            'connection' => basename($connection),
            'queue'      => basename($queue),
            'payload'    => file_get_contents($filename),
            'failed_at'  => \DateTime::createFromFormat('YmdHis', $ts)->format('Y-m-d H:i:s'),
        ];
    }


}
