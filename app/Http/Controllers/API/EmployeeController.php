<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CrudTrait;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;

class EmployeeController extends Controller
{
    use CrudTrait;

    public function __construct() {
        $this->model = Employee::class;
        $this->resource = EmployeeResource::class;
        $this->syncEntity = 'departments';
    }
}
