<?php

namespace App\Models;

use App\Models\Traits\PositionTrait;
use Illuminate\Database\Eloquent\Model;

class EmployeeFunctionalGroup extends Model
{
    use PositionTrait;

    protected $table = 'employees_functional_groups';

    public function __construct(array $attributes = []) {
        $this->name_field = 'functional_group_id';
        parent::__construct($attributes);
    }
}
