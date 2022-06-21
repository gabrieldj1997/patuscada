<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartasBrancasController;
use App\Http\Controllers\CartasPretasController;
use App\Http\Controllers\JogoController;
use App\Http\Controllers\UserOfflineController;
use App\Http\Controllers\UserOnlineController;
use App\Http\Controllers\UserController;
use App\Events\MessageJogo;
use App\Events\JogadasJogo;
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

Route::controller(CartasPretasController::class)->prefix('cartaspretas')->name('cartaspretas.')->group(function(){
    Route::get('/', 'GetCartasPretas')->name('getall');
    Route::get('/{id}', 'GetCartaPreta')->name('get');
    Route::post('/', 'RegisterCartaPreta')->name('register');
    Route::put('/{id}', 'AlterCartaPreta')->name('alter');
});

Route::controller(CartasBrancasController::class)->prefix('cartasbrancas')->name('cartasbrancas.')->group(function(){
    Route::get('/', 'GetCartasBrancas')->name('getall');
    Route::get('/{id}', 'GetCartaBranca')->name('get');
    Route::post('/', 'RegisterCartaBranca')->name('register');
    Route::put('/{id}', 'AlterCartaBranca')->name('alter');
});

Route::controller(JogoController::class)->prefix('jogoApi')->name('jogoApi.')->group(function(){
    Route::post('/start', 'StartPartida')->name('start');
    Route::post('/next', 'FinalizarRodada')->name('next');
    Route::get('/find/{codigo}', 'FindPartida')->name('find');
    //Eventos de Jogo
    Route::post('/{jogoId}/cartapreta', 'ChooseCartaPreta')->name('chossecartapreta');
    Route::post('/{jogoId}/cartabranca', 'ChooseCartaBranca')->name('chossecartabranca');
    Route::post('/{jogoId}/vencedor', 'ChooseJogadorVencedor')->name('chossejogadorvencedor');
    Route::post('/{jogoId}/changeCartas', 'ChangeCartasBrancas')->name('changecartas');
    //Teste
    Route::post('/{id}/teste', function(Request $req, $id){
        event(
            new MessageJogo($id, ['tp_message' => [5, 5], 'message' => 'Mensagem de teste'])

        );
        event(
            new JogadasJogo($id, $req->input('my_id'), 1, $req->input('id_carta_preta'))
        );
    })->name('teste');
    Route::get('/{id}/jogador', 'TesteJogadores')->name('testeJogadores');
});

Route::controller(UserController::class)->prefix('user')->name('user.')->group(function(){
    Route::get('/{id}', 'GetUser')->name('get');
});