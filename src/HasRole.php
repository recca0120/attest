<?php

namespace Recca0120\Attest;

trait HasRole
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($roles)
    {
        $operator = 'and';

        if (func_num_args() > 1) {
            $roles = func_get_args();
        } elseif (is_string($roles) === true) {
            if (strpos($roles, '|') !== false) {
                $operator = 'or';
                $roles = explode('|', $roles);
            } else {
                $roles = explode(',', $roles);
            }

            $roles = array_map('trim', $roles);
        }

        $roles = is_array($roles) === false ? [$roles] : $roles;

        $names = array_map(function ($role) {
            return $role instanceof Role ? $role->name : $role;
        }, $roles);

        $counts = $this->roles->filter(function ($role) use ($names) {
            return in_array($role->name, $names) === true;
        })->count();

        return $operator === 'and' ? $counts === count($names) : $counts > 0;
    }
}
