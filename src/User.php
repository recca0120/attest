<?php

namespace Recca0120\Attest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Recca0120\Attest\Concerns\HasRolesAndPermissions;

class User extends Model
{
    use HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return MorphToMany
     */
    public function roles()
    {
        return $this->morphToMany(Role::class, 'roleable', 'roleable');
    }
}
