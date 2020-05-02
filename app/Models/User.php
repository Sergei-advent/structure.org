<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'id', 'token', 'email', 'role_id'
    ];

    public function role() {
        return $this->hasOne(Role::class);
    }
}
