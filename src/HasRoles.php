<?php

namespace Recca0120\Attest;

trait HasRoles
{
    use Ownable;

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($roles)
    {
        if (func_num_args() > 1) {
            return call_user_func([$this, 'hasRole'], func_get_args());
        }

        return $this->require($this->roles, $roles);
    }
}
