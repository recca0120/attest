<?php

namespace Recca0120\Attest\Concerns;

use Recca0120\Attest\Permission;

trait HasPermissions
{
    use Permissible;

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'permissible', 'permissible')->withPivot([
            'permissible_id', 'permissible_type', 'permission_id',
        ]);
    }

    public function hasPermission($permissions)
    {
        return $this->permit($this->permissions, func_num_args() > 1 ? func_get_args() : $permissions);
    }
}
