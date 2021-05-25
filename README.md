

| Author      | Version | Date |
| :---        |    :----:   |   :----:   |
| Erika Leonor Basurto Munguia      | V1.1       | 25 May 2021|

<br>
<br>

# Custom provider that lets you decide whether to persist the failed job log

1. Horizon
    > php artisan horizon

2. Run queue
    > php artisan queue:work <queue_name>

3. Run Job by Command
    > php artisan run:<job_name>

<br>
---
# Configuration 

1. Add key **"ignore_jobs"** in the file **"config/queue.php"**
2. Add name job that you wish ignore when that fails
    
    For example
    ```
    'ignore_jobs' =>[
        App\Jobs\SendMsnJob::class
    ]

## References
https://aregsar.com/blog/2020/configure-laravel-queue-to-use-redis/

https://github.com/pmatseykanets/file-queue-failer
