<?php

namespace Recca0120\Attest\Concerns;

use Recca0120\Attest\Permission;

trait HasPermissions
{
    use Permissible;

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'permission_granted')->withPivot([
            'permission_granted_id',
            'permission_granted_type',
            'permission_id',
        ]);
    }

    public function hasPermission($permissions)
    {
        return $this->permit(
            $this->permissions,
            func_num_args() > 1 ? func_get_args() : $permissions
        );
    }
}
