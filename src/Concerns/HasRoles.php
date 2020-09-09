<?php

namespace Recca0120\Attest\Concerns;

trait HasRoles
{
    use Permissible;

    /**
     * @param $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        return $this->permit($this->roles, func_num_args() > 1 ? func_get_args() : $roles);
    }
}
