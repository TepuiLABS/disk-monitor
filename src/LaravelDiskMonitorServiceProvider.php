<?php

namespace Tepuilabs\LaravelDiskMonitor;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Tepuilabs\LaravelDiskMonitor\Commands\RecordDiskMetricsCommand;
use Tepuilabs\LaravelDiskMonitor\Http\Controllers\DiskMetricsController;

class LaravelDiskMonitorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this
            ->registerPublishables()
            ->registerCommands()
            ->registerRoutes()
            ->registerViews();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-disk-monitor.php', 'laravel-disk-monitor');
    }

    protected function registerPublishables(): self
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-disk-monitor.php' => config_path('laravel-disk-monitor.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/laravel-disk-monitor'),
            ], 'views');

            $migrationFileName = 'create_disk_monitor_tables.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }
        }

        return $this;
    }

    protected function registerCommands(): self
    {
        if (! $this->app->runningInConsole()) {
            return $this;
        }

        $this->commands([
            RecordDiskMetricsCommand::class,
        ]);

        return $this;
    }

    protected function registerRoutes(): self
    {
        Route::macro('diskMonitor', function (string $prefix) {
            Route::prefix($prefix)->group(function () {
                Route::get('/', '\\' . DiskMetricsController::class);
            });
        });

        return $this;
    }

    protected function registerViews(): self
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-disk-monitor');

        return $this;
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
