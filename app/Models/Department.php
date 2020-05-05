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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function childDepartments() {
        return $this->belongsTo(Department::class, 'parent_department_id', 'id');
    }

    /**
     * Relation to Employee model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employees() {
        return $this->belongsTo(Employee::class);
    }
}
