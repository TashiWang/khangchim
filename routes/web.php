<?php

use App\Http\Controllers\Admins\AdminDashboardController;
use App\Http\Controllers\Admins\LandlordController;
use App\Http\Controllers\Admins\RoleController;
use App\Http\Controllers\Admins\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Landlords\AreaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use \App\Http\Controllers\Landlords\HouseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum', 'verified', 'role:admin|owner|developer'])->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
    Route::resource('roles', RoleController::class)->except(['create', 'show', 'edit']);
    Route::resource('landlords', LandlordController::class)->except(['create', 'show', 'edit']);
    Route::resource('areas', AreaController::class)->except(['create', 'show', 'edit']);
    Route::resource('houses', HouseController::class)->except(['create', 'show', 'edit']);
    Route::get('', function() {
        return Inertia::render('Admins/Profile/Show');
    });
});

Route::prefix('landlord')->name('landlord.')->middleware(['auth:sanctum', 'verified', 'role:owner'])->group(function () {

});

Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    $limiter = config('fortify.limiters.login');
    // $verificationLimiter = config('fortify.limiters.verification', '6,1');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest:' . config('fortify.guard'),
            $limiter ? 'throttle:' . $limiter : null,
        ]));
});
