<?php

namespace App\Http\Controllers;

use App\Http\Requests\JogoRequest;
use Illuminate\Http\Request;
use App\Models\Jogo;
use App\Models\Rodadas;
use Illuminate\Support\Facades\Auth;
use App\Models\CartasBrancas;

class JogoController extends Controller
{
    function Index()
    {
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

    function IniciarPartida(Request $request)
    {
        try {
            $jogo = Jogo::find($request->id_jogo);
            $rodada = new Rodadas();
            $rodada->id_jogo = $jogo->id;
            $rodada->rodada = 0;
            $rodada->id_estado_rodada = 0;
            $rodada->id_jogador_mestre = -1;
            $rodada->jogadores = json_encode(json_decode($request->jogadores->map(function($jogador){return ["id"=>$jogador->id,"pontuacao"=>0];})));
            
            $allWhiteCards = CartasBrancas::all()->get('id');
            $allWhiteCards = json_encode(json_decode($allWhiteCards->map(function($card){return $card->id;})));

            $allBlackCards = CartasBrancas::all()->get('id');
            $allBlackCards = json_encode(json_decode($allBlackCards->map(function($card){return $card->id;})));

            $rodada->cartas_brancas_monte = $allWhiteCards;
            $rodada->cartas_brancas_descartardas = [];
            $rodada->cartas_pretas_monte = $allBlackCards;
            $rodada->cartas_pretas_descartardas = [];

            $rodada->cartas_brancas_jogador = json_encode(json_decode($request->jogadores->map(function($jogador){return ["id_jogador"=>$jogador, "cartas"=>[]];})));
            $rodada->cartas_pretas_jogador = [];

            $rodada->save();

            $jogo->jogadores = $request->jogadores;
            $jogo->status = 1;
            $jogo->save();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function DistribuirCartas(Request $req){
        try {
            $rodada = Rodadas::find($req->id_jogo);

            $cartas_brancas_monte = json_decode($req->cartas_brancas_monte);
            $cartas_pretas_monte = json_decode($req->cartas_pretas_monte);
            $jogadores = json_decode($rodada->jogadores);

            for($i = 0; $i < $jogadores->count(); $i++){
                $cartas = $jogadores[$i]->cartas->count();
                for($x = 5 - $cartas; $x > 0; $x--){
                    $random = rand(0, count($cartas_brancas_monte) - 1);
                    $cartaRandom = array_splice($cartas_brancas_monte, $random, 1);
                    $jogadores[$i]->cartas->push($cartaRandom[0]);
                }
            }


        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
