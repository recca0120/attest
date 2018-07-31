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
        if (func_num_args() > 1) {
            return call_user_func([$this, 'hasPermission'], func_get_args());
        }

        return $this->require($this->permissions, $permissions);
    }
}
