<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'token', 'email', 'role_id', 'token_active_before'
    ];

    /**
     *  Relation to Role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function role() {
        return $this->hasOne(Role::class);
    }
}
