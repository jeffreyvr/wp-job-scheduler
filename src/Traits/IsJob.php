<?php

namespace Jeffreyvr\WPJobScheduler\Traits;

use Throwable;

trait IsJob
{
    private bool $cancel = false;

    private bool $success = false;

    private bool $failed = false;

    private ?Throwable $exception = null;

    public function withDelay(int $delay): self
    {
        $this->delay = $delay;

        return $this;
    }

    public function withProperties(array $properties): self
    {
        foreach ($properties as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    public function failed(Throwable $e): self
    {
        $this->failed = true;

        $this->exception = $e;

        $this->catch($e);

        return $this;
    }

    public function hasFailed(): bool
    {
        return $this->failed;
    }

    public function hasSucceeded(): bool
    {
        return $this->success;
    }

    public function success(): self
    {
        $this->success = true;

        return $this;
    }

    public function cancel(): self
    {
        $this->cancel = true;

        return $this;
    }

    public function cancelled(): bool
    {
        return $this->cancel;
    }

    public function before(): void
    {
        //
    }

    public function after(): void
    {
        //
    }

    public function catch(Throwable $e): void
    {
        //
    }

    public function handle()
    {
        //
    }

    public static function payload($arguments)
    {
        return [
            'dispatch' => [
                'job' => static::class,
                'arguments' => $arguments,
            ],
        ];
    }
}
