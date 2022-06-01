<?php

namespace App\Http\Controllers;

use App\Http\Requests\JogoRequest;
use Illuminate\Http\Request;
use App\Models\Jogo;
use Illuminate\Support\Facades\Auth;

class JogoController extends Controller
{
    function Index(){
        return redirect()->route('login.index');
    }

    function RegisterGame(JogoRequest $request)
    {

        $jogo = new Jogo();
        $jogo->codigo = $request->codigo;
        $jogo->nome_jogo = $request->nome_jogo;
        $jogo->user_master = Auth::user()->id;
        $jogo->save();
        return redirect('/jogo/partida/' . $jogo->id);
    }

    function Partida($id)
    {
        $jogo = Jogo::find($id);
        return view('jogo.partida', compact('jogo'));
    }

    function GetJogo($id)
    {
        $jogo = Jogo::find($id);
        return response()->json($jogo);
    }

    function FindPartida($codigo)
    {
        try {
            $jogo = Jogo::where('codigo', $codigo)->first();
            return response()->json($jogo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
