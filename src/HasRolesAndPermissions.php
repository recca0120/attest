<?php

namespace Recca0120\Attest;

trait HasRolesAndPermissions
{
    use HasRoles;
    use HasPermissions {
        permissions as userPermissions;
        HasRoles::permit insteadof HasPermissions;
        HasRoles::permitAnd insteadof HasPermissions;
        HasRoles::permitOr insteadof HasPermissions;
        HasRoles::permitOne insteadof HasPermissions;
        HasRoles::permitAll insteadof HasPermissions;
        HasRoles::getPermissibleName insteadof HasPermissions;
    }

    public function permissions()
    {
        $roles = $this->roles();
        $roleClass = get_class($roles->getModel());

        return $this
            ->userPermissions()
            ->unionAll(
                $roles
                    ->join('permission_granteds', function ($join) use ($roleClass) {
                        $join->on('permission_granted_type', '=', $roleClass)
                            ->on('permission_granted_id', '=', 'role_user.role_id');
                    })
                    ->join('permissions', function ($join) {
                        $join->on('permissions.id', '=', 'permission_granteds.permission_id');
                    })
                    ->select('permissions.*')
                    ->selectRaw('permission_granteds.permission_granted_type AS pivot_permission_granted_type')
                    ->selectRaw('permission_granteds.permission_granted_id AS pivot_permission_granted_id')
                    ->selectRaw('permission_granteds.permission_id AS pivot_permission_id')
            )
            ->distinct('permissions.id');
    }
}
