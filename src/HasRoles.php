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
        return $this->require(
            $this->roles,
            func_num_args() > 1 ? func_get_args() : $roles
        );
    }
}
