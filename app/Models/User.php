<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'id', 'token', 'email', 'role_id', 'token_active_before'
    ];

    public function role() {
        return $this->hasOne(Role::class);
    }
}
