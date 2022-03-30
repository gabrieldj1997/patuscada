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
    return view('chat.chat', ['nickname' => Auth::user()->nickname]);
})->middleware('auth');

Route::controller(LoginController::class)->prefix('login')->name('login.')->group(function () {
    //rotas front-end
    Route::get('/', function(){return redirect('/login/entrar');});
    Route::get('/entrar', 'Index')->name('index');
    Route::get('/cadastro', 'Register')->name('cadaster');
    //rotas back-end
    Route::get('/cadastrar', 'RegisterLogin')->name('register');
    Route::get('/autenticate', 'AutenticateLogin')->name('autenticate'); 
    Route::put('/update', 'UpdateLogin')->name('update');
    Route::delete('/delete', 'DeleteLogin')->name('delete');
    Route::get('/truncate', 'Truncate')->name('truncate');
    Route::get('/logout', 'Logout')->name('logout');
    Route::post('/captcha', 'Captcha')->name('captcha');
});

Route::controller(JogoController::class)->middleware('auth')->prefix('jogo')->name('jogo')->group(function () {
    //rotas front-end
    Route::get('/', 'Jogo')->name('jogoIndex');
    Route::get('/criar', 'Register');
    Route::get('/partida/{id}', 'GameOn');
    //rotas back-end
    Route::post('/partida/{id}', 'VerifyGame');
    Route::post('/criar', 'RegisterGame');
    Route::post('/resetar/{id}', 'ResetGame');
});