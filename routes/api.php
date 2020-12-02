<?php


use App\Http\Controllers\API\{
    TestController,
    PermissionController,
    FunctionalGroupController as FunctionalGroupControllerForGetTreeView
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth_test', [TestController::class, 'authTest']);

Route::middleware(['main.auth:api'])->group(function () {
    Route::get('/group/tree', [FunctionalGroupControllerForGetTreeView::class, 'getTree']);

    Route::get('/permissions', [PermissionController::class, 'getPermissionFromPage']);

    Route::apiResources([
        'department' => DepartmentController::class,
        'employee' => EmployeeController::class,
        'position' => PositionController::class,
        'group' => FunctionalGroupController::class,
    ]);

    Route::resource('structure', OrgStructureController::class)
        ->only(['store', 'index'])
        ->middleware('xml');
});
