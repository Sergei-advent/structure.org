<?php

use Illuminate\Http\Request;
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

Route::post('/auth_test', 'API\TestController@authTest');

Route::middleware(['main.auth:api'])->group(function () {
    Route::post('/structure', 'API\OrgStructureController@store')->middleware('xml');
    Route::get('/structure', 'API\OrgStructureController@index');

    Route::resource('/department', 'API\DepartmentController')->except(['create', 'edit']);
    Route::resource('/employee', 'API\EmployeeController')->except(['create', 'edit']);
    Route::resource('/position', 'API\PositionController')->except(['create', 'edit']);
    Route::resource('/group', 'API\FunctionalGroupController')->except(['create', 'edit']);
});
