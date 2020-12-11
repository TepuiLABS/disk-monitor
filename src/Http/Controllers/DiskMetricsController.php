<?php
namespace Tepuilabs\LaravelDiskMonitor\Http\Controllers;

use Tepuilabs\LaravelDiskMonitor\Models\DiskMonitorEntry;

class DiskMetricsController
{
    public function __invoke()
    {
        $entries = DiskMonitorEntry::latest()->get();

        return view('laravel-disk-monitor::entries', compact('entries'));
    }
}
