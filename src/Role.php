<?php

namespace Recca0120\Attest;

use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsToMany(User::class);
    }
}
