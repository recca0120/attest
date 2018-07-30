<?php

namespace Recca0120\Attest;

trait HasRoles
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($roles)
    {
        if (func_num_args() > 1) {
            return call_user_func([$this, 'hasRole'], func_get_args());
        }

        if (is_string($roles) === true) {
            if ($matched = $this->operatorAnd($this->roles, $roles) !== false) {
                return $matched;
            }

            if ($matched = $this->operatorOr($this->roles, $roles) !== false) {
                return $matched;
            }
        }

        return $this->requireAll(
            $this->roles,
            $this->getTargetName(is_array($roles) === true ? $roles : [$roles])
        );
    }

    protected function operatorAnd($sources, $targets)
    {
        $pattern = '/(\sand\s|&&|&|,)/i';
        if ((bool) preg_match($pattern, $targets) === false) {
            return false;
        }

        return $this->requireAll($sources, preg_split($pattern, $targets));
    }

    protected function operatorOr($sources, $targets)
    {
        $pattern = '/(\sor\s|\|\||\|)/i';
        if ((bool) preg_match($pattern, $targets) === false) {
            return false;
        }

        return $this->requireOne($sources, preg_split($pattern, $targets));
    }

    protected function requireOne($sources, $targets)
    {
        foreach ($targets as $target) {
            if ($sources->containsStrict('name', trim($target)) === true) {
                return true;
            }
        }

        return false;
    }

    protected function requireAll($sources, $targets)
    {
        foreach ($targets as $target) {
            if ($sources->containsStrict('name', trim($target)) === false) {
                return false;
            }
        }

        return true;
    }

    protected function getTargetName(array $targets)
    {
        return array_map(function ($target) {
            return is_object($target) ? $target->name : $target;
        }, $targets);
    }
}
