<?php

namespace Jeffreyvr\WPJobScheduler;

use ReflectionClass;

class Handler
{
    public static function createInstanceFromPayload($payload)
    {
        // ray($payload);
        $reflectionClass = new ReflectionClass($payload['job']);
        $instance = $reflectionClass->newInstanceArgs($payload['arguments']);

        foreach ($payload['properties'] ?? [] as $key => $value) {
            if (isset($value)) {
                continue;
            }

            $instance->{$key} = $value;
        }

        return $instance;
    }

    public static function createPayloadFromInstance($instance)
    {
        $reflection = new ReflectionClass($instance);
        $constructorParams = array_column($reflection->getConstructor()->getParameters(), null, 'name');

        $properties = [];

        foreach ($reflection->getProperties() as $property) {
            if (! $property->isPublic()) {
                continue;
            }

            $name = $property->getName();

            $properties[$name] = $property->getValue($instance);

            if (array_key_exists($name, $constructorParams)) {
                $constructorParams[$name] = $properties[$name];
            }
        }

        return [
            'dispatch' => [
                'job' => get_class($instance),
                'arguments' => $constructorParams,
                'properties' => $properties,
            ],
        ];
    }

    public static function handle($payload = [])
    {
        ray($payload)->red();

        $instance = self::createInstanceFromPayload($payload);

        $instance->before();

        if ($instance->cancelled()) {
            return;
        }

        try {
            $instance->handle();
        } catch (\Exception $e) {
            $instance->failed($e);

            return;
        }

        $instance->success();

        $instance->after();
    }
}
