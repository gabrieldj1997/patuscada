<?php

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Events\Message;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\JogoController;
use GuzzleHttp\Middleware;

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
    //rotas front-end
    Route::get('/', function(){return redirect('login/entrar');})->middleware('guest');
    Route::get('/entrar', 'Index')->name('loginIndex');
    Route::get('/cadastro', 'Register');
    //rotas back-end
    Route::post('/cadastro', 'RegisterLogin')->name('registerLogin');
    Route::post('/get', 'GetLogin'); 
    Route::put('/update', 'UpdateLogin');
    Route::delete('/delete', 'DeleteLogin');
    Route::get('/truncate', 'Truncate');
    Route::get('/logout', 'Logout');
});

Route::controller(JogoController::class)->middleware('auth')->prefix('jogo')->group(function () {
    //rotas front-end
    Route::get('/', 'Jogo')->name('jogoIndex');
    Route::get('/criar', 'Register');
    Route::get('/partida/{id}', 'GameOn');
    //rotas back-end
    Route::post('/partida/{id}', 'VerifyGame');
    Route::post('/criar', 'RegisterGame');
    Route::post('/resetar/{id}', 'ResetGame');
});