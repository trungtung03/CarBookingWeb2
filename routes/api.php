<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\CarController;

// Routes for authentication
Route::group(['middleware' => 'api'], function ($routes) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
    Route::get('/send-verify-mail/{email}', [AuthController::class, 'sendVerifyMail']);
    Route::get('/verify-email', [AuthController::class, 'verifyEmail']);
});
Route::group(['middleware' => 'api'], function ($routes) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
    Route::get('/send-verify-mail/{email}', [AuthController::class, 'sendVerifyMail']);
    Route::get('/verify-email', [AuthController::class, 'verifyEmail']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/details', [UserController::class, 'getUserDetails']);
    Route::post('/user/update-profile', [UserController::class, 'updateUserProfile']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);
});

Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'index']);
    Route::post('/admin/users', [AdminController::class, 'store']);
    Route::put('/admin/users/{id}', [AdminController::class, 'update']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy']);
});       
Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    Route::get('/admin/cars', [CarController::class, 'index']);
    Route::post('/admin/cars', [CarController::class, 'store']);
    Route::put('/admin/cars/{id}', [CarController::class, 'update']);
    Route::delete('/admin/cars/{id}', [CarController::class, 'destroy']);
});
Route::get('/driver/dashboard', [DriverController::class, 'dashboard'])->name('driver.dashboard');
Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');

