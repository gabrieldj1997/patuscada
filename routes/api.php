<?php

use App\Http\Controllers\UserOnlineController;
use App\Http\Controllers\UserOfflineController;
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

Route::put('user/{user}/online', UserOnlineController::class);
Route::put('user/{user}/offline', UserOfflineController::class);