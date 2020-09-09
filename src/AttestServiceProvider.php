<?php

namespace Recca0120\Attest;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;

class AttestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @param \Illuminate\Contracts\Auth\Access\Gate
     *
     * @return void
     */
    public function boot(Gate $gate)
    {
        $gate->define('role', function ($user, $roles) {
            return $user->hasRole($roles);
        });

        $gate->define('permission', function ($user, $permission) {
            return $user->hasPermission($permission);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole() === true) {
            $this->publishes([__DIR__.'/../database/migrations' => base_path('database/migrations')], 'migrations');

            return;
        }
    }
}
