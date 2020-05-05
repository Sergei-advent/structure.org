<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'other_information', 'code_name'
    ];

    /**
     * Relation to Employee model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employees() {
        return $this->belongsTo(Employee::class);
    }
}
