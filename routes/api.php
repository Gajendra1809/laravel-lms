<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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

Route::post('/users', [UserController::class, 'store']);
Route::post('/users/login', [AuthController::class, 'login']);
Route::post('forget-password/request-token', [AuthController::class, 'requestToken']);
Route::post('forget-password/reset', [AuthController::class, 'resetPassword']);

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('/users/search', [UserController::class, 'search']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/show/{uuid}', [UserController::class, 'show'])->middleware('check_valid_uuid');
    Route::put('/users/{uuid}', [UserController::class, 'update'])->middleware('check_valid_uuid');
    Route::delete('/users/{uuid}', [UserController::class, 'destroy'])->middleware('check_valid_uuid');
    Route::get('/users/logout', [AuthController::class, 'logout']);

});
