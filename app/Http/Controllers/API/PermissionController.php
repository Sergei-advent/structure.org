<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function getPermissionFromPage(Request $request) {
        $entity = $request->get('entity');
        $userToken = $request->header('token');

        $user = User::getUserForToken($userToken);

        $rolePermissions = RolePermission::where(['entity' => $entity, 'role_id' => $user->role_id])->get();

        $permissions = [];

        foreach ($rolePermissions as $rolePermission) {
            $permissions[] = $rolePermission->permission->code_name;
        }

        return response()->json($permissions, JsonResponse::HTTP_OK);
    }
}
