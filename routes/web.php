<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Events\Message;
use App\Http\Controllers\LoginController;

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
    return view('index');
});

Route::post('/send-message', function (Request $request) {
    event(
        new Message(
            $request->input('username'),
            $request->input('message')
        )
    );

    return response()->json(['status' => 'ok']);
});

Route::group(['prefix' => 'login', 'as' => 'Login'], function () {
    Route::get('/', [LoginController::class, 'Login']);
    Route::get('/cadastro', [LoginController::class, 'Cadaster']);
    Route::post('/', [LoginController::class, 'RegisterLogin'])->name('registerLogin');
    Route::post('/{id}', [LoginController::class, 'GetLogin']);
    Route::put('/{id}', [LoginController::class, 'UpdateLogin']);
    Route::delete('/{id}', [LoginController::class, 'DeleteLogin']);
    Route::get('/truncate', [LoginController::class, 'Truncate']);
});