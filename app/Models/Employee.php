<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    public $position = null;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'other_information'
    ];

    /**
     * Relation to Department model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function departments() {
        return $this->belongsToMany(Department::class, 'employees_departments');
    }

    /**
     * Relation to FunctionalGroup model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function functionalGroups() {
        return $this->belongsToMany(Department::class, 'employees_functional_groups');
    }
}
