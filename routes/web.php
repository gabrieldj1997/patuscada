<?php

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Events\Message;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\JogoController;
use GuzzleHttp\Middleware;
use App\Models\User;

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
})->name('index');

Route::post('/send-message', function (Request $request) {
    event(
        new Message(
            $request->input('nickname'),
            $request->input('message')
        )
    );

    return response()->json(['status' => 'ok']);
});

Route::get('/chat', function () {
    return view('chat.chat');
})->middleware('auth')->name('chat');

Route::controller(LoginController::class)->prefix('login')->name('login.')->group(function () {
    //rotas front-end
    Route::get('/entrar', 'Index')->name('index');
    Route::get('/cadastro', 'Register')->name('cadaster');
    //rotas back-end
    Route::post('/cadastrar', 'RegisterLogin')->name('register');
    Route::post('/autenticate', 'AutenticateLogin')->name('autenticate'); 
    Route::get('/users-online', 'UsersOnline')->name('usersOnline');
    Route::put('/update', 'UpdateLogin')->name('update');
    Route::post('/delete/{id}', 'DeleteLogin')->name('delete');
    Route::get('/truncate', 'Truncate')->name('truncate');
    Route::get('/logout', 'Logout')->name('logout');
    Route::post('/captcha', 'Captcha')->name('captcha');
});

Route::controller(JogoController::class)->middleware('auth')->prefix('jogo')->name('jogo.')->group(function () {
   Route::get('/{id}', 'Partida')->name('partida');
   Route::get('/create', 'CreatePartida')->name('create');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
