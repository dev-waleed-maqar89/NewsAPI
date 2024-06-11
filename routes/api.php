<?php

use App\Http\Controllers\Api\V1\ApiAdminController;
use App\Http\Controllers\Api\V1\AdminNewsController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserNewsController;
use App\Http\Resources\Main\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
    //User roots
    Route::post('register', [UserController::class, 'register'])->middleware('ApiGuestMiddleware:sanctum');
    Route::post('login', [UserController::class, 'login'])->middleware('ApiGuestMiddleware:sanctum');
    Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('profile', [UserController::class, 'profile'])->middleware('auth:sanctum');
    Route::post('update', [UserController::class, 'update'])->middleware('auth:sanctum');
    // Admin control routes
    Route::post('admin/store', [ApiAdminController::class, 'store'])->middleware(['auth:sanctum', 'ApiAdminMiddleware:supervisor']);
    Route::get('admin', [ApiAdminController::class, 'index'])->middleware(['auth:sanctum', 'ApiAdminMiddleware:supervisor, moderator']);
    Route::get('admin/{admin}', [ApiAdminController::class, 'show'])->middleware(['auth:sanctum', 'ApiAdminMiddleware:supervisor, moderator']);
    Route::get('admin/{admin}/update', [ApiAdminController::class, 'update'])->middleware(['auth:sanctum', 'ApiAdminMiddleware:supervisor']);
    Route::get('admin/{admin}/destroy', [ApiAdminController::class, 'destroy'])->middleware(['auth:sanctum', 'ApiAdminMiddleware:supervisor']);
    // News control
    Route::group(['prefix' => 'dashboard'], function () {
        Route::apiResource('news', AdminNewsController::class);
        Route::post('news/{news}/addImage', [AdminNewsController::class, 'addImage'])->middleware(['auth:sanctum', 'ApiAdminMiddleware']);
        Route::put('news/{news}/publish', [AdminNewsController::class, 'publish'])->middleware(['auth:sanctum', 'ApiAdminMiddleware']);
    });
    // News explore
    Route::get('news', [UserNewsController::class, 'index'])->middleware('auth:sanctum');
    Route::get('news/{id}', [UserNewsController::class, 'show'])->middleware('auth:sanctum');
    Route::post('news/{id}/like', [UserNewsController::class, 'like'])->middleware('auth:sanctum');
});