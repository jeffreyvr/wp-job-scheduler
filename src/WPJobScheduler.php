<?php

namespace Jeffreyvr\WPJobScheduler;

class WPJobScheduler
{
    private static $instance;

    public ?Schedule $schedule = null;

    public string|null $projectRootPath = null;

    public string $group = 'wp_job_scheduler';

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function group($name)
    {
        $this->group = $name;

        return $this;
    }

    public function projectRootPath($path)
    {
        $this->projectRootPath = $path;

        return $this;
    }

    public function loadActionScheduler()
    {
        require_once $this->projectRootPath.'/vendor/woocommerce/action-scheduler/action-scheduler.php';
    }

    public function scheduler($callback)
    {
        $this->loadActionScheduler();

        $this->schedule = new Schedule();

        $this->schedule->name($this->group);

        $callback($this->schedule);

        return $this;
    }

    public function boot()
    {
        $this->schedule->finish();

        add_action('wp_job_scheduler_handler', [Handler::class, 'handle'], 10, 1);
    }
}
