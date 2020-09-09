<?php

namespace Recca0120\Attest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug'];

    /**
     * @return MorphToMany
     */
    public function roles()
    {
        return $this->morphedByMany(Role::class, 'permissible')->withPivot([
            'permissible_id', 'permissible_type', 'permission_id',
        ]);
    }

    /**
     * @return MorphToMany
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'permissible')->withPivot([
            'permissible_id', 'permissible_type', 'permission_id',
        ]);
    }
}
