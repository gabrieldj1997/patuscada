<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login;

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


Route::get('login', [Login::class, 'GetAllPlayers']);
Route::post('login', [Login::class, 'CreatePlayer']);
// Route::get('login/{id}', 'LoginController@GetPlayer');
// Route::put('login/{id}', 'LoginController@UpdatePlayer');
// Route::delete('login/{id}', 'LoginController@DeletePlayer');