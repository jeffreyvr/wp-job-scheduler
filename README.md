> 🚧 This package is still work in progress

# WP Job Schedular

This package is an attempt to make job dispatching and scheduling easier in WordPress.

Inspired by the syntax of Laravel and using `Action Scheduler` from WooCommerce, for more reliable handeling.

## Installation

```bash
composer require jeffreyvanrossum/wp-job-schedular
```

## Usage

### Setup

Within your theme or plugin, add:

```php
WPJobScheduler::instance()
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
    $job->withDelay(30); // seconds
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
* [All contributors](https://github.com/jeffreyvr/wp-settings/graphs/contributors)

## License
MIT. Please see the [License File](/LICENSE) for more information.
