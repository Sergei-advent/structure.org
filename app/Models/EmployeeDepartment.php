<?php

namespace App\Models;

use App\Models\Traits\PositionTrait;
use Illuminate\Database\Eloquent\Model;

class EmployeeDepartment extends Model
{
    use PositionTrait;

    protected $table = 'employees_departments';

    public function __construct(array $attributes = []) {
        $this->name_field = 'department_id';
        parent::__construct($attributes);
    }
}
