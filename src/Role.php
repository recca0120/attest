<?php

namespace Recca0120\Attest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Recca0120\Attest\Concerns\HasPermissions;

class Role extends Model
{
    use HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug'];

    /**
     * @return MorphToMany
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'roleable', 'roleable');
    }
}
