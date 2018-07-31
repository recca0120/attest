<?php

namespace Recca0120\Attest;

trait HasPermissions
{
    use Ownable;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasPermission($permissions)
    {
        return $this->require(
            $this->permissions,
            func_num_args() > 1 ? func_get_args() : $permissions
        );
    }
}
