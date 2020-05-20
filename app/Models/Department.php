<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_department_id', 'name', 'code_name', 'description', 'other_information'
    ];

    /**
     * Relation to parent Department model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parentDepartment() {
        return $this->hasOne(Department::class, 'id', 'parent_department_id');
    }

    /**
     * Relation to child Department model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childDepartments() {
        return $this->hasMany(Department::class, 'parent_department_id', 'id');
    }

    /**
     * Relation to Employee model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function employees() {
        return $this->belongsToMany(Employee::class, 'employees_departments');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function positions() {
        return $this->belongsToMany(Position::class, 'employees_departments', 'department_id', 'position_id');
    }
}
