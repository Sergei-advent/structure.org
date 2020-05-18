<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CrudTrait;
use App\Http\Resources\PositionResource;
use App\Models\Position;

class PositionController extends Controller
{
    use CrudTrait;

    public function __construct() {
        $this->model = Position::class;
        $this->resource = PositionResource::class;
    }
}
