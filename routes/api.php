<?php

use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ROLES ROUTES
Route::get('roles', [RoleController::class, 'index']);
Route::prefix('/role')->group(function () {
    Route::post('/store', [RoleController::class, 'store']);
    Route::put('/{id}', [RoleController::class, 'update']);
    Route::delete('/{id}', [RoleController::class, 'destroy']);
});

// USERS ROUTE
Route::get('users', [UserController::class, 'index']);
Route::prefix('/user')->group(function () {
    Route::post('/store', [UserController::class, 'store']);
    Route::put('/{id}}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::get('permissions', [PermissionController::class, 'index']);
Route::prefix('/permission')->group(function () {
    Route::post('/store', [PermissionController::class, 'store']);
    Route::put('/{id}}', [PermissionController::class, 'update']);
    Route::delete('/{id}', [PermissionController::class, 'destroy']);
});
