<?php

namespace Recca0120\Attest;

trait HasPermissions
{
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
