<?php

namespace Recca0120\Attest;

use Illuminate\Database\Eloquent\Model;
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

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
