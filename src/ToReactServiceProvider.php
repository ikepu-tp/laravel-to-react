<?php

namespace ikepu_tp\ToReact;

use ikepu_tp\ToReact\app\Console\Commands\InstallCommand;
use Illuminate\Support\ServiceProvider;

class ToReactServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (!$this->app->runningInConsole()) return;
        $this->commands([
            InstallCommand::class,
        ]);
    }
}
