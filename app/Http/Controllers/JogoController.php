<?php

namespace App\Http\Controllers;

use App\Http\Requests\JogoRequest;
use Illuminate\Http\Request;
use App\Models\Jogo;
use App\Models\Rodadas;
use Illuminate\Support\Facades\Auth;
use App\Models\CartasBrancas;
use App\Models\CartasPretas;
use App\Events\MessageJogo;
use App\Events\HostJogo;

class JogoController extends Controller
{
    public function Partida($id)
    {
        $jogo = Jogo::find($id);
        return view('jogo.partida', ['jogo' => $jogo]);
    }

    public function CreatePartida(Request $req)
    {
        $jogo = new Jogo();
        $jogo->codigo_jogo = $req->input('codigo_jogo');
        $jogo->id_jogador_criador = Auth::user()->id;

        $cartas_brancas = CartasBrancas::all('id');
        $cartas_brancas = json_decode($cartas_brancas->map(function ($item) {
            return $item->id;
        })->toJson());
        $jogo->cartas_brancas_monte = json_encode($cartas_brancas);

        $cartas_pretas = CartasPretas::all('id');
        $cartas_pretas = json_decode($cartas_pretas->map(function ($item) {
            return $item->id;
        })->toJson());
        $jogo->cartas_pretas_monte = json_encode($cartas_pretas);

        $jogo->save();
        return redirect()->route('jogo.partida', ['jogo' => $jogo]);
    }

    public function StartPartida(Request $req)
    {
        event(
            new MessageJogo($req->input('id'), 'Iniciando partida...')
        );
        $jogo = Jogo::find($req->input('id'));
        if (Auth::user()->id != $jogo->id_jogador_criador) {
            return json_encode(["error" => "Você não é o host do jogo"]);
        }
        $jogo->rodada_jogo = 1;
        $jogo->estado_jogo = 1;

        $jogadores = $req->input('jogadores');
        $jogadores = json_decode($jogadores->map(function ($item) {
            return ["jogador" => $item, "pontuacao" => array(), "cartas_brancas" => array()];
        }));
        $jogo->jogadores = json_encode($jogadores);
        
        $jogo->save();

        $jogo = $this->DistribuirCartas($jogo);
        event(
            new MessageJogo($jogo->id, 'Partida iniciada')
        );
        return json_encode($jogo);
    }

    public function DistribuirCartas($jogo)
    {
        event(
            new MessageJogo($jogo->id, 'Distribuindo cartas...')
        );
        $jogo = Jogo::find($jogo->id);
        $jogadores = json_decode($jogo->jogadores);
        $cartas_monte = json_decode($jogo->cartas_brancas_monte);
        foreach ($jogadores as $jogador) {
            for ($i = count($jogador->cartas); $i < 5; $i++) {
                array_push($jogador->cartas, array_slice($cartas_monte, rand(0, count($cartas_monte) - 1)));
            }
        };
        $jogo->jogadores = json_encode($jogadores);
        $jogo->cartas_brancas_monte = json_encode($cartas_monte);

        $cartas_pretas_monte = json_decode($jogo->cartas_pretas_monte);
        $cartas_pretas_jogo = json_decode($jogo->cartas_pretas_jogo);
        for ($i = count($cartas_pretas_jogo); $i < 3; $i++) {
            array_push($cartas_pretas_jogo, $cartas_pretas_monte[rand(0, count($cartas_pretas_monte) - 1)]);
        }
        $jogo->cartas_pretas_jogo = json_encode($cartas_pretas_jogo);

        $jogo->save();
        event(
            new MessageJogo($jogo->id, 'Cartas distribuidas')
        );
        return $jogo;
    }

    public function FinalizarRodada(Request $req)
    {
        $jogo = Jogo::find($req->input('id'));
        if(Auth::user()->id != $jogo->id_jogador_criador) {
            return json_encode(["error" => "Você não é o host do jogo"]);
        }
        event(
            new MessageJogo($req->id, 'Finalizando rodada...')
        );
        $jogadores = json_decode($jogo->jogadores);
        $cartas_brancas_descartadas = json_decode($req->cartas_brancas_descartadas);
        $jogador_ganhador = json_decode($req->jogador_ganhador);
        $carta_preta_descartada = json_decode($req->input('carta_preta_descartada'));
        $cartas_pretas = json_decode($jogo->cartas_pretas_monte);

        //pontuando jogador ganhador
        array_push(
            $jogadores[array_search($jogador_ganhador->id_jogador, array_column($jogadores, 'jogador'))]->pontuacao,
            [
                "carta_preta" => $jogador_ganhador->id_carta_preta,
                "carta_branca" => $jogador_ganhador->id_carta_branca
            ]
        );

        if(count(json_decode($jogo->cartas_brancas_monte)) < count($jogadores) || count($cartas_pretas) < 3) {
            $this->FinalizarPartida($jogo->id);
            $jogador_vencedor = ["id_jogador"=>0, "pontuacao"=>0];
            foreach($jogadores as $jogador) {
                if(count($jogador->pontuacao) > $jogador_vencedor["pontuacao"]) {
                    $jogador_vencedor = ["id_jogador"=>$jogador->jogador, "pontuacao"=>count($jogador->pontuacao)];
                }
            }
            event(
                new MessageJogo($jogo->id, 'Jogador vencedor: '.$jogador_vencedor["id_jogador"])
            );
            return json_encode(["message" => "Partida finalizada","jogador_vencedor"=>$jogador_vencedor["id_jogador"]]);
        }
        
        //retirando da mão dos jogadores a carta que foi jogada
        foreach ($cartas_brancas_descartadas as $cartas_descartadas) {
            $id_jogador = $cartas_descartadas->id_jogador;
            foreach($cartas_descartadas as $carta){
                array_slice($jogadores[array_search($id_jogador, array_column($jogadores, 'jogador'))]->cartas_brancas, $carta, 1);
            }
        }

        $jogo->jogadores = json_encode($jogadores);

        //retirando do jogo a carta preta descartada
        array_slice($cartas_pretas, array_search($carta_preta_descartada, $cartas_pretas), 1);
        $jogo->cartcartas_pretas_monteas_pretas_jogo = json_encode($cartas_pretas);
        $jogo->cartas_pretas_jogo = json_encode(array());
        $jogo->save();

        event(
            new MessageJogo($req->id, 'Rodada Finalizada')
        );

        return json_encode($jogo);
    }

    public function ProximaRodada(Request $req)
    {
        event(
            new MessageJogo($req->id, 'Iniciando próxima rodada...')
        );
        $jogo = Jogo::find($req->input('id'));
        $jogo->rodada_jogo++;
        $jogo->save();
        $this->DistribuirCartas($req);
        event(
            new MessageJogo($req->id, 'Rodada iniciada')
        );
    }

    public function FinalizarPartida($id_jogo){
        $jogo = Jogo::find($id_jogo);
        $jogo->estado_jogo = 2;
        $jogo->save();
        event(
            new MessageJogo($id_jogo, 'Partida finalizada')
        );
    }
}
