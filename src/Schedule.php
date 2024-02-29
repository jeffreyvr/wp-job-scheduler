<?php

namespace Jeffreyvr\WPJobScheduler;

class Schedule
{
    public array $previous = [];

    public array $current = [];

    public ?string $name = null;

    public function name($name)
    {
        $this->name = sanitize_key($name);

        return $this;
    }

    public function getName()
    {
        return sanitize_key($this->name);
    }

    public function add($job)
    {
        $this->current[md5(serialize($job))] = $job;
    }

    public function current()
    {
        ksort($this->current);

        return $this->current;
    }

    public function previous()
    {
        ksort($this->previous);

        return $this->previous;
    }

    public function removeJobsThatNoLongerExist()
    {
        foreach ($this->previous() as $key => $job) {
            if (! array_key_exists($key, $this->current())) {
                as_unschedule_action('wp_job_scheduler_handler', $job, $this->getName());
            }
        }
    }

    public function update()
    {
        if ($this->current() !== $this->previous()) {
            update_option($this->getName(), $this->current());
        }
    }

    public function finish()
    {
        $this->previous = get_option($this->getName(), []);

        $this->removeJobsThatNoLongerExist();

        $this->update();

        return $this;
    }
}
