<?php

namespace Recca0120\Attest;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'name'];

    public function roles()
    {
        return $this->belongsToMany(User::class);
    }
}
