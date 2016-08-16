<?php

namespace BeautyCoding\Revision\Providers;

use Illuminate\Support\ServiceProvider;

class RevisionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/revision.php' => config_path('revision.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
