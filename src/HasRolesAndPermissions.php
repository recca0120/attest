<?php

namespace Recca0120\Attest;

trait HasRolesAndPermissions
{
    use HasRoles;
    use HasPermissions {
        permissions as userPermissions;
        HasRoles::require insteadof HasPermissions;
        HasRoles::operatorAnd insteadof HasPermissions;
        HasRoles::operatorOr insteadof HasPermissions;
        HasRoles::requireOne insteadof HasPermissions;
        HasRoles::requireAll insteadof HasPermissions;
        HasRoles::getTargetName insteadof HasPermissions;
    }

    public function permissions()
    {
        return $this->userPermissions()->unionAll(
            $this->roles()
                ->join('permission_role', function ($query) {
                    $query->on('roles.id', '=', 'permission_role.role_id');
                })
                ->join('permissions', function ($query) {
                    $query->on('permissions.id', '=', 'permission_role.permission_id');
                })
                ->select('permissions.*')
                ->selectRaw('role_user.user_id AS pivot_user_id')
                ->selectRaw('permission_role.permission_id AS pivot_permission_id')
        )->distinct();
    }
}
