<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CrudTrait;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;

class DepartmentController extends Controller
{
    use CrudTrait;

    public function __construct() {
        $this->model = Department::class;
        $this->resource = DepartmentResource::class;
        $this->syncEntity = 'employees';
    }
}
