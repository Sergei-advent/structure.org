<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CrudTrait;
use App\Http\Resources\FunctionalGroupResource;
use App\Models\FunctionalGroup;

class FunctionalGroupController extends Controller
{
    use CrudTrait;

    public function __construct() {
        $this->model = FunctionalGroup::class;
        $this->resource = FunctionalGroupResource::class;
        $this->syncEntity = 'employees';
    }
}
