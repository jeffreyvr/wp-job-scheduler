<?php

namespace Jeffreyvr\WPJobScheduler\Traits;

use Jeffreyvr\WPJobScheduler\Handler;
use Jeffreyvr\WPJobScheduler\WPJobScheduler;

trait Schedulable
{
    public static function intervalFromRecurrence($recurrence)
    {
        $schedules = wp_get_schedules();

        return $schedules[$recurrence]['interval'] ?? false;
    }

    public static function validateScheduling($payload, $recurrence)
    {
        if (\as_next_scheduled_action('wp_job_scheduler_handler', $payload, WPJobScheduler::instance()->group)) {
            return false;
        }

        return self::intervalFromRecurrence($recurrence);
    }

    public static function schedule($recurrence, $callback = null, $arguments = [])
    {
        $initialPayload = self::payload($arguments);

        $job = Handler::createInstanceFromPayload($initialPayload['dispatch']);

        $initialPayload['dispatch']['recurrence'] = $recurrence;

        if (is_callable($callback)) {
            $callback($job);
        }

        $payload = array_merge($initialPayload, Handler::createPayloadFromInstance($job));

        WPJobScheduler::instance()->schedule->add($payload);

        if (\as_next_scheduled_action('wp_job_scheduler_handler', $payload, WPJobScheduler::instance()->group)) {
            return;
        }

        if (! $interval = self::intervalFromRecurrence($recurrence)) {
            return;
        }

        \as_schedule_recurring_action(time(), $interval, 'wp_job_scheduler_handler', $payload, WPJobScheduler::instance()->group);
    }
}
