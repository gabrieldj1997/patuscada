<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jogo;
use Illuminate\Support\Facades\Auth;
use App\Models\CartasBrancas;
use App\Models\CartasPretas;
use App\Models\JogadorCartas;
use App\Events\MessageJogo;
use App\Events\JogadasJogo;
// use App\Events\CartasJogo;

class JogoController extends Controller
{
    public function Partida($id)
    {
        $jogo = Jogo::find($id);
        $jogadores = JogadorCartas::where('id_jogo', $id)->get();
        return view('jogo.partida', ['jogo' => $jogo, 'jogadores' => $jogadores]);
    }

    public function CreatePartida(Request $req)
    {
        $jogo = new Jogo();
        $jogo->codigo = $req->input('codigo_jogo');
        $jogo->id_jogador_criador = Auth::user()->id;

        $cartas_brancas = CartasBrancas::all('id')->take(30);
        $cartas_brancas = json_decode($cartas_brancas->map(function ($item) {
            return $item->id;
        })->toJson());
        $jogo->cartas_brancas_monte = json_encode($cartas_brancas);

        $cartas_pretas = CartasPretas::all('id')->take(10);
        $cartas_pretas = json_decode($cartas_pretas->map(function ($item) {
            return $item->id;
        })->toJson());
        $jogo->cartas_pretas_monte = json_encode($cartas_pretas);
        $jogo->cartas_pretas_jogo = json_encode(array());

        $jogo->save();
        return redirect()->route('jogo.partida', [$jogo->id, 'jogo' => $jogo]);
    }

    public function StartPartida(Request $req)
    {
        $jogo = Jogo::find($req->input('id_jogo'));
        // event(
        //     new MessageJogo($jogo->id, ['tp_message' => [1, 1], 'message' => 'Iniciando partida...'])
        // );

        if ($req->input('id_user') != $jogo->id_jogador_criador) {
            return json_encode(["error" => "Você não é o host do jogo"]);
        }
        $jogo->estado_jogo = 1;

        $jogadores = $req->input('jogadores');
        foreach ($jogadores as $jogador) {
            $jogador_carta = new JogadorCartas();
            $jogador_carta->id_jogo = $jogo->id;
            $jogador_carta->id_jogador = $jogador;
            $jogador_carta->cartas = json_encode(array());
            $jogador_carta->pontuacao = json_encode(array());
            $jogador_carta->save();
        }

        $jogo->save();
        event(
            new MessageJogo($jogo->id, ['tp_message' => [1, 2], 'message' => 'Partida iniciada'])
        );
        
        $this->ProximaRodada($req);
    }

    public function DistribuirCartas($jogo)
    {
        // event(
        //     new MessageJogo($jogo->id, ['tp_message' => [2, 1], 'message' => 'Distribuindo cartas...'])

        // );
        $jogadores = JogadorCartas::where('id_jogo', $jogo->id)->get();
        $cartas_brancas_monte = json_decode($jogo->cartas_brancas_monte);

        foreach ($jogadores as $jogador) {
            $jogador_cartas = json_decode($jogador->cartas);
            for ($i = count($jogador_cartas); $i < 5; $i++) {
                $carta_branca_do_monte = array_splice($cartas_brancas_monte, rand(0, count($cartas_brancas_monte) - 1), 1);
                array_push($jogador_cartas, $carta_branca_do_monte[0]);
            }
            $jogador->cartas = json_encode($jogador_cartas);
            $jogador->save();
        };
        
        $jogo->cartas_brancas_monte = json_encode($cartas_brancas_monte);

        $cartas_pretas_monte = json_decode($jogo->cartas_pretas_monte);
        $cartas_pretas_jogo = json_decode($jogo->cartas_pretas_jogo);

        for ($i = count($cartas_pretas_jogo); $i < (3 < count($cartas_pretas_monte) ? 3 : count($cartas_pretas_monte)); $i++) {
            $carta = $cartas_pretas_monte[(rand(0, count($cartas_pretas_monte) - 1))];
            array_push($cartas_pretas_jogo, $carta);
        }

        $jogo->cartas_pretas_monte = json_encode($cartas_pretas_monte);
        $jogo->cartas_pretas_jogo = json_encode($cartas_pretas_jogo);

        // for ($i = 0; $i < count($jogadores); $i++) {
        //     event(
        //         new CartasJogo($jogo->id,$jogadores[$i]->jogador, $jogadores[$i]->cartas_brancas)
        //     );
        // }

        $jogo->save();

        event(
            new MessageJogo($jogo->id, ['tp_message' => [2, 2], 'message' => 'Cartas distribuidas'])
        );
    }

    public function FinalizarRodada(Request $req)
    {
        // return json_encode(
        //     [
        //         'um' => $req->input('id'),
        //         'dois' => $req->input('jogador_ganhador'),
        //         'tres' => $req->input('carta_preta_descartada'),
        //         'quarto' => $req->input('cartas_brancas_descartadas'),
        //     ]);
        $jogo = Jogo::find($req->input('id_jogo'));
        if ($req->input('my_id') != $jogo->id_jogador_criador) {
            return json_encode(["error" => "Você não é o host do jogo"]);
        }
        // event(
        //     new MessageJogo($jogo->id, ['tp_message' => [3, 3], 'message' => 'Finalizando rodada...'])

        // );
        $jogadores = JogadorCartas::where('id_jogo', $jogo->id)->get();
        $cartas_brancas_descartadas = json_decode($req->input('cartas_brancas_descartadas'));
        $jogador_ganhador = json_decode($req->input('jogador_ganhador'));
        $carta_preta_descartada = json_decode($req->input('carta_preta_descartada'));
        $carta_branca_ganhadora = json_decode($req->input('carta_branca_ganhadora'));
        $cartas_pretas_monte = json_decode($jogo->cartas_pretas_monte);

        //pontuando jogador ganhador
        $indexJogador = array_search($jogador_ganhador->id_jogador, array_column(json_decode($jogadores), 'id_jogador'));
        $jogador_vencedor = $jogadores[$indexJogador];
        $pontuacao = json_decode($jogador_vencedor->pontuacao);
        array_push($pontuacao, array($carta_branca_ganhadora, $carta_preta_descartada));
        $jogador_vencedor->pontuacao = json_encode($pontuacao);
        $jogador_vencedor->save();
        $jogadores[$indexJogador] = $jogador_vencedor;

        $count_cartas_descartadas = 0;
        foreach ($cartas_brancas_descartadas as $cartas_descartadas){
            foreach($cartas_descartadas->cartas as $carta){
                $count_cartas_descartadas ++;
            }
        }
        if (
            count(json_decode($jogo->cartas_brancas_monte)) < count($jogadores) 
            || (count(json_decode($jogo->cartas_pretas_monte)) + count(json_decode($jogo->cartas_pretas_jogo))) == 0
            || count(json_decode($jogo->cartas_brancas_monte)) < $count_cartas_descartadas
            ){
            $this->FinalizarPartida($jogo->id);
            $jogador_vencedor = ["id_jogador" => 0, "pontuacao" => 0];
            foreach ($jogadores as $jogador) {
                if (count(json_decode($jogador->pontuacao)) > $jogador_vencedor['pontuacao']) {
                    $jogador_vencedor = ["id_jogador" => $jogador->id_jogador, "pontuacao" => count(json_decode($jogador->pontuacao))];
                }
            }
            event(
                new MessageJogo($jogo->id, ['tp_message' => [1, 3], 'message' => 'Jogador vencedor: ' . $jogador_vencedor["id_jogador"] . '. Pontuacao: ' . $jogador_vencedor["pontuacao"]])
            );
            return json_encode(["message" => "Partida finalizada", "jogador_vencedor" => $jogador_vencedor["id_jogador"]]);
        }
        //retirando da mão dos jogadores a carta que foi jogada
        foreach ($cartas_brancas_descartadas as $cartas_descartadas) {
            $id_jogador = $cartas_descartadas->id;
            foreach ($cartas_descartadas->cartas as $carta) {
                $indJogador = array_search($id_jogador, array_column(json_decode($jogadores), 'id_jogador'));
                $indCarta = array_search($carta, json_decode($jogadores[$indJogador]->cartas));
                $array_cartas = json_decode($jogadores[$indJogador]->cartas);
                array_splice($array_cartas, $indCarta, 1);
                $jogadores[$indJogador]->cartas = json_encode($array_cartas);
                $jogadores[$indJogador]->save();
            }
        }



        //retirando do jogo a carta preta descartada
        array_slice($cartas_pretas_monte, array_search($carta_preta_descartada, $cartas_pretas_monte), 1);
        $jogo->cartas_pretas_monte = json_encode($cartas_pretas_monte);
        $jogo->cartas_pretas_jogo = json_encode(array());
        $jogo->save();

        // event(
        //     new MessageJogo($jogo->id, ['tp_message' => [3, 4], 'message' => 'Rodada Finalizada'])
        // );
        $this->ProximaRodada($req);
    }

    public function ProximaRodada(Request $req)
    {
        $jogo = Jogo::find($req->input('id_jogo'));

        // event(
        //     new MessageJogo($jogo->id, ['tp_message' => [3, 1], 'message' => 'Iniciando proxima rodada'])
        // );
        $jogo->rodada_jogo++;
        $jogo->save();

        $jogadores = JogadorCartas::where('id_jogo', $jogo->id)->get();

        // event(
        //     new MessageJogo($jogo->id, ['tp_message' => [3, 2], 'message' => 'Rodada iniciada', 'jogador_leitor' => $jogadores[$jogo->rodada_jogo % count($jogadores)]->id_jogador])
        // );

        $this->DistribuirCartas($jogo);
    }

    public function FinalizarPartida($id_jogo)
    {
        $jogo = Jogo::find($id_jogo);
        $jogo->estado_jogo = 2;
        $jogo->save();
    }

    public function FindPartida($codigo)
    {
        $jogo = Jogo::where('codigo', $codigo)->first();
        if ($jogo == null) {
            return json_encode(["error" => "Jogo não encontrado"]);
        }
        return json_encode($jogo);
    }

    public function ChooseCartaPreta(Request $req, $jogoId)
    {
        $carta_preta = CartasPretas::find($req->input('id_carta_preta'));

        event(
            new JogadasJogo(
                $jogoId,
                $req->input('my_id'),
                1,
                $carta_preta
            )
        );
    }

    public function ChooseCartaBranca(Request $req, $jogoId)
    {
        $carta_branca = CartasBrancas::find($req->input('id_carta_branca'));

        event(
            new JogadasJogo(
                $jogoId,
                $req->input('my_id'),
                2,
                $carta_branca
            )
        );
    }

    public function ChooseJogadorVencedor(Request $req, $jogoId)
    {
        $carta_branca = CartasBrancas::find($req->input('id_carta_branca'));
        $carta_preta = CartasPretas::find($req->input('id_carta_preta'));

        event(
            new JogadasJogo(
                $jogoId,
                $req->input('my_id'),
                3,
                ["id_jogador" => $req->input('id_jogador_ganhador'), "id_carta_preta" => $carta_preta->id, "id_carta_branca" => $carta_branca->id]
            )
        );
    }

    public function ChangeCartasBrancas(Request $req, $jogoId){

        $jogador_carta = JogadorCartas::where('id_jogador',$req->input('my_id'))->where('id_jogo',$jogoId)->first();
        event(
            new JogadasJogo(
                $jogoId,
                $req->input('my_id'),
                4,
                json_decode($jogador_carta->cartas)
            )
        );
    }

    public function TesteJogadores(Request $req, $id)
    {
        $jogo = Jogo::find($id);
        $jogadores = JogadorCartas::where('id_jogo', $jogo->id)->get();
        $cartas_brancas_monte = json_decode($jogo->cartas_brancas_monte);
        foreach ($jogadores as $jogador) {
            $jogador_cartas = json_decode($jogador->cartas);
            for ($i = count($jogador_cartas); $i < 5; $i++) {
                $carta_branca_do_monte = array_splice($cartas_brancas_monte, rand(0, count($cartas_brancas_monte) - 1), 1);
                array_push($jogador_cartas, $carta_branca_do_monte[0]);
            }
            $jogador->cartas = json_encode($jogador_cartas);
            $jogador->save();
        };
        return $jogadores;
    }
}
