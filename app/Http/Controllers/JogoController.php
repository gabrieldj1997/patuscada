<?php

namespace App\Http\Controllers;
use Illuminate\Auth;
use Illuminate\Http\Request;
use App\Models\Jogo;

class JogoController extends Controller
{
    private $jogos;

    function __construct(){
        $this->jogos = array();
    }

    function Jogo(){
        return view('game.game');
    }
    function Register(){
        return view('game.register');
    }
    function GameOn($id){
        return view('game.gameon');
    }
    function VerifyGame($id){
        
    }
    function RegisterGame(Request $request){
        $jogo = new Jogo();
        $jogo->nome = $request->input('nome');
        $jogo->save();
        return redirect('/jogo');
    }
    function ResetGame(Request $request, $id){
        $jogo = Jogo::find($id);
        $jogo->resetar();
        return redirect('/jogo/partida/'.$id);
    }
}
