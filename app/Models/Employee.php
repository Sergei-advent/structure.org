<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'department_id', 'name', 'other_information', 'position_id'
    ];

    /**
     * Relation to Department model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function department() {
        return $this->hasOne(Department::class);
    }

    /**
     * Relation to Position model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function position() {
        return $this->hasOne(Position::class);
    }
}
