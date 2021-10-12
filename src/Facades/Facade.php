<?php

declare(strict_types=1);

namespace App\Facades;

abstract class Facade
{
    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public static function __callStatic(string $method, array $args)
    {
        $className = static::getFacadeAccessor();
        if (!method_exists($className, $method)) {
            throw new \Exception('Facade instance not found');
        }
        return $className->$method(...$args);
    }
}
