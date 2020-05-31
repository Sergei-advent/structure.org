<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CrudTrait;
use App\Http\Resources\FunctionalGroupResource;
use App\Models\FunctionalGroup;
use Illuminate\Http\JsonResponse;

class FunctionalGroupController extends Controller
{
    use CrudTrait;

    public function __construct() {
        $this->model = FunctionalGroup::class;
        $this->resource = FunctionalGroupResource::class;
        $this->syncEntity = 'employees';
    }

    public function getTree() {
        return response()->json(
            FunctionalGroupResource::collection(
                FunctionalGroup::doesntHave('parentFunctionalGroup')->get()
            ), JsonResponse::HTTP_OK
        );
    }
}
