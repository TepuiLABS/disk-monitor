<?php

namespace Tepuilabs\LaravelDiskMonitor;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tepuilabs\LaravelDiskMonitor\LaravelDiskMonitor
 */
class LaravelDiskMonitorFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-disk-monitor';
    }
}
