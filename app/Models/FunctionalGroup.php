<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FunctionalGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_functional_group_id', 'name', 'code_name', 'description', 'other_information'
    ];

    /**
     * Relation to parent Department model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parentFunctionalGroup() {
        return $this->hasOne(FunctionalGroup::class, 'id', 'parent_functional_group_id');
    }

    /**
     * Relation to child Department model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childFunctionalGroups() {
        return $this->hasMany(FunctionalGroup::class, 'parent_functional_group_id', 'id');
    }

    /**
     * Relation to Employee model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function employees() {
        return $this->belongsToMany(Employee::class, 'employees_functional_groups');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function positions() {
        return $this->belongsToMany(Position::class, 'employees_functional_groups', 'functional_group_id', 'position_id');
    }
}
