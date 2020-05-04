<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function authTest(Request $request) {
        return response()->json(['email' => 'test@test.test', 'token' => 'test'], JsonResponse::HTTP_OK);
    }
}
