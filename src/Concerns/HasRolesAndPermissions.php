<?php

namespace Recca0120\Attest\Concerns;

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
                    ->join('permissible', function ($join) use ($roleClass) {
                        $join->on('permissible_type', '=', $roleClass)->on('permissible_id', '=', 'role_user.role_id');
                    })
                    ->join('permissions', function ($join) {
                        $join->on('permissions.id', '=', 'permissible.permission_id');
                    })
                    ->select('permissions.*')
                    ->selectRaw('permissible.permissible_type AS pivot_permissible_type')
                    ->selectRaw('permissible.permissible_id AS pivot_permissible_id')
                    ->selectRaw('permissible.permission_id AS pivot_permission_id')
            )
            ->distinct('permissions.id');
    }
}
