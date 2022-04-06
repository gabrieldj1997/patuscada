<?php

namespace App\Http\Controllers;

use App\Http\Requests\JogoRequest;
use Illuminate\Http\Request;
use App\Models\Jogo;
use Illuminate\Support\Facades\Auth;

class JogoController extends Controller
{
    function RegisterGame(JogoRequest $request){

        $jogo = new Jogo();
        $jogo->codigo = $request->codigo;
        $jogo->nome_jogo = $request->nome_jogo;
        $jogo->user_master = Auth::user()->id;
        $jogo->save();
        return redirect('/jogo/partida/{$jogo->id}');
    }
}
