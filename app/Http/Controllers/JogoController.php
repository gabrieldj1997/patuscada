<?php

namespace App\Http\Controllers;
use Illuminate\Auth;
use Illuminate\Http\Request;
use App\Models\Jogo;
use App\Models\Jogos;

class JogoController extends Controller
{
    function Jogo(){
        return view('game.game');
    }
    function Register(){
        return view('game.register');
    }
    function GameOn($id){
        $jogo = Jogo::find($id);
        return view('game.gameon', ['jogo' => $jogo]);
    }
    function VerifyGame($id){
        
    }
    function RegisterGame(Request $request){
        $request->validate([
            'codGame' => 'required',
            'nameGame' => 'required'
        ]);

        $jogo = new Jogo();
        $jogo->codigo = $request->codGame;
        $jogo->nome_jogo = $request->nameGame;
        $jogo->players = "[]";
        $jogo->save();

        $jogos = new Jogos();
        $jogos->addJogos($jogos);

        $jogo = Jogo::where('codigo', $request->codGame)->first();
        return redirect('/jogo/partida/{$jogo->id}');
    }
    function ResetGame(Request $request, $id){
        $jogo = Jogo::find($id);
        $jogo->resetar();
        return redirect('/jogo/partida/'.$id);
    }
}
