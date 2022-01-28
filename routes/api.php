<?php

use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\HouseController;
use App\Http\Controllers\Api\LandlordController;
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
    Route::put('/{role}', [RoleController::class, 'update']);
    Route::get('/{role}', [RoleController::class, 'show']);
    Route::delete('/{role}', [RoleController::class, 'destroy']);
});

// USERS ROUTE
Route::get('users', [UserController::class, 'index']);
Route::prefix('/user')->group(function () {
    Route::post('/store', [UserController::class, 'store']);
    Route::put('/{user}}', [UserController::class, 'update']);
    Route::get('/{user}}', [UserController::class, 'show']);
    Route::delete('/{user}', [UserController::class, 'destroy']);
});

// LANDLORDS ROUTES
Route::get('landlords', [LandlordController::class, 'index']);
Route::prefix('/landlord')->group(function () {
    Route::post('/store', [LandlordController::class, 'store']);
    Route::put('/{landlord}', [LandlordController::class, 'update']);
    Route::get('/{landlord}', [LandlordController::class, 'show']);
    Route::delete('/{landlord}', [LandlordController::class, 'destroy']);
});

// AREA ROUTES
Route::get('areas', [AreaController::class, 'index']);
Route::prefix('/area')->group(function () {
    Route::post('/store', [AreaController::class, 'store']);
    Route::put('/{area}', [AreaController::class, 'update']);
    Route::get('/{area}', [AreaController::class, 'show']);
    Route::delete('/{area}', [AreaController::class, 'destroy']);
});

// HOUSE ROUTES
Route::get('houses', [HouseController::class, 'index']);
Route::prefix('/house')->group(function () {
    Route::post('/store', [HouseController::class, 'store']);
    Route::put('/{house}', [HouseController::class, 'update']);
    Route::get('/{house}', [HouseController::class, 'show']);
    Route::delete('/{house}', [HouseController::class, 'destroy']);
});
