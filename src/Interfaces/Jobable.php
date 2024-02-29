<?php

namespace Jeffreyvr\WPJobScheduler\Interfaces;

use Throwable;

interface Jobable
{
    public function handle();

    public function cancel();

    public function before();

    public function after();

    public function failed(Throwable $e);

    public function catch(Throwable $e);
}
