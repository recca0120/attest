<?php

namespace Recca0120\Attest;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug'];

    public function roles()
    {
        return $this->morphedByMany(Role::class, 'permissible')->withPivot([
            'permissible_id',
            'permissible_type',
            'permission_id',
        ]);
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'permissible')->withPivot([
            'permissible_id',
            'permissible_type',
            'permission_id',
        ]);
    }
}
