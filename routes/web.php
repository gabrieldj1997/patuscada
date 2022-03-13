<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Events\Message;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\JogoController;

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

Route::get('/chat', function () {
    return view('chat');
});

Route::controller(LoginController::class)->prefix('login')->group(function () {
    Route::get('/', 'Login')->name('login');
    Route::get('/cadastro', [LoginController::class, 'Register']);
    Route::post('/', [LoginController::class, 'RegisterLogin'])->name('registerLogin');
    Route::post('/{id}', [LoginController::class, 'GetLogin']);
    Route::put('/{id}', [LoginController::class, 'UpdateLogin']);
    Route::delete('/{id}', [LoginController::class, 'DeleteLogin']);
    Route::get('/truncate', [LoginController::class, 'Truncate']);
});

Route::controller(JogoController::class)->prefix('jogo')->group(function () {
    Route::get('/', 'Jogo')->name('jogo');
    Route::get('/{id}', [JogoController::class, 'GetJogo']);
    Route::post('/', [JogoController::class, 'CreateJogo']);
    Route::put('/{id}', [JogoController::class, 'UpdateJogo']);
    Route::delete('/{id}', [JogoController::class, 'DeleteJogo']);
    Route::get('/truncate', [JogoController::class, 'Truncate']);
});