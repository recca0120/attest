<?php

namespace Recca0120\Attest;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate;

class AttestServiceProvider extends ServiceProvider
{
    public function boot(Gate $gate)
    {
        $gate->define('role', function ($user, $roles) {
            return $user->hasRole($roles);
        });

        $gate->define('permission', function ($user, $permission) {
            return $user->hasPermission($permission);
        });
    }
}
