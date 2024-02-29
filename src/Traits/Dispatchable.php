<?php

namespace Jeffreyvr\WPJobScheduler\Traits;

use Jeffreyvr\WPJobScheduler\Handler;
use Jeffreyvr\WPJobScheduler\WPJobScheduler;

trait Dispatchable
{
    private int $delay = 0;

    public static function dispatch($callback = null, $arguments = [])
    {
        $initialPayload = self::payload($arguments);

        $job = Handler::createInstanceFromPayload($initialPayload['dispatch']);

        if (is_callable($callback)) {
            $callback($job);
        }

        $payload = array_merge($initialPayload, Handler::createPayloadFromInstance($job));

        \as_schedule_single_action(time() + $job->delay, 'wp_job_scheduler_handler', $payload, WPJobScheduler::instance()->group);
    }
}
