<?php
namespace Tepuilabs\LaravelDiskMonitor\Models;

use Illuminate\Database\Eloquent\Model;

class DiskMonitorEntry extends Model
{
    public $guarded = [];

    public $casts = [
        'file_count' => 'integer',
    ];

    public static function last(): ?self
    {
        return static::latest()->first();
    }
}
