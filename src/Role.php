<?php

namespace Recca0120\Attest;

use Illuminate\Database\Eloquent\Model;
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

    public function users()
    {
        return $this->morphedByMany(User::class, 'roleable', 'roleable');
    }
}
