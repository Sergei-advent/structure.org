<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const DEFAULT_ROLE = 1;

    protected $fillable = [
        'id', 'name', 'code_name', 'description'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
