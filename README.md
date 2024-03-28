<p align="center"><a href="https://vanrossum.dev" target="_blank"><img src="https://raw.githubusercontent.com/jeffreyvr/vanrossum.dev-art/main/logo.svg" width="320" alt="vanrossum.dev Logo"></a></p>

<p align="center">
<a href="https://packagist.org/packages/jeffreyvanrossum/wp-job-scheduler"><img src="https://img.shields.io/packagist/dt/jeffreyvanrossum/wp-job-scheduler" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/jeffreyvanrossum/wp-job-scheduler"><img src="https://img.shields.io/packagist/v/jeffreyvanrossum/wp-job-scheduler" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/jeffreyvanrossum/wp-job-scheduler"><img src="https://img.shields.io/packagist/jeffreyvanrossum/wp-job-scheduler" alt="License"></a>
</p>

# WP Job Scheduler

> 🚧 This package is still work in progress

This package is an attempt to make job dispatching and scheduling easier in WordPress.

Inspired by the syntax of Laravel and using [Action Scheduler](https://github.com/woocommerce/action-scheduler/) from WooCommerce, for more reliable handeling.

## Installation

```bash
composer require jeffreyvanrossum/wp-job-scheduler
```

## Usage

### Setup

Within your theme or plugin, add:

```php
WPJobScheduler::instance()
    ->projectRootPath(__DIR__)
    ->scheduler(function() {
        ExampleJob::schedule('hourly');
    })
    ->boot();
```

### Dispatching single jobs

```php
ExampleJob::dispatch();
```

You may pass a callback to do some changes on the job before it is dispatched:

```php
ExampleJob::dispatch(function($job) {
    $job->withDelay(30);
});
```

You may also pass some initial constructor arguments:

```php
ExampleJob::dispatch(function($job) {
    $job->withDelay(30);
}, ['foo' => 'bar']);
```

## Example Job

```php
class ExampleJob implements Jobable
{
    use IsJob, Schedulable, Dispatchable;

    public function handle()
    {
        // the actual handling of the job
    }

    public function before(): void
    {
        // before the job has been handled
    }

    public function after(): void
    {
        // after the job has been handled
    }

    public function catch(Throwable $e): void
    {
        // do something with exception
    }
}
```

## Contributors
* [Jeffrey van Rossum](https://github.com/jeffreyvr)
* [All contributors](https://github.com/jeffreyvr/wp-job-scheduler/graphs/contributors)

## License
MIT. Please see the [License File](/LICENSE) for more information.
