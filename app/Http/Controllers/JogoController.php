<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jogo;
use Illuminate\Support\Facades\Auth;
use App\Models\CartasBrancas;
use App\Models\CartasPretas;
use App\Events\MessageJogo;
use App\Events\JogadasJogo;
// use App\Events\CartasJogo;

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
        $jogo->codigo = $req->input('codigo_jogo');
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
        $jogo->cartas_pretas_jogo = json_encode(array());

        $jogo->save();
        return redirect()->route('jogo.partida', [$jogo->id, 'jogo' => $jogo]);
    }

    public function StartPartida(Request $req)
    {
        $jogo = Jogo::find($req->input('id_jogo'));
        event(
            new MessageJogo($jogo->id, ['tp_message' => [1, 1], 'message' => 'Iniciando partida...'])
        );

        if ($req->input('id_user') != $jogo->id_jogador_criador) {
            return json_encode(["error" => "Você não é o host do jogo"]);
        }
        $jogo->estado_jogo = 1;

        $jogadores = $req->input('jogadores');
        $jogadores = array_map(function ($id) {
            return ["jogador" => $id, "pontuacao" => array(), "cartas_brancas" => array()];
        }, $jogadores);
        $jogo->jogadores = json_encode($jogadores);

        $jogo->save();
        event(
            new MessageJogo($jogo->id, ['tp_message' => [1, 2], 'message' => 'Partida iniciada'])
        );
        $this->ProximaRodada($req);
    }

    public function DistribuirCartas($jogo)
    {
        event(
            new MessageJogo($jogo->id, ['tp_message' => [2, 1], 'message' => 'Distribuindo cartas...'])

        );

        $jogo = Jogo::find($jogo->id);
        $jogadores = json_decode($jogo->jogadores);
        $cartas_monte = json_decode($jogo->cartas_brancas_monte);

        foreach ($jogadores as $jogador) {
            for ($i = count($jogador->cartas_brancas); $i < 5; $i++) {
                $carta_preta_do_monte = array_splice($cartas_monte, rand(0, count($cartas_monte) - 1), 1);
                array_push($jogador->cartas_brancas, $carta_preta_do_monte);
            }
        };
        $jogo->jogadores = json_encode($jogadores);
        $jogo->cartas_brancas_monte = json_encode($cartas_monte);

        $cartas_pretas_monte = json_decode($jogo->cartas_pretas_monte);
        $cartas_pretas_jogo = json_decode($jogo->cartas_pretas_jogo);

        for ($i = count($cartas_pretas_jogo); $i < 3; $i++) {
            $carta = $cartas_pretas_monte[rand(0, count($cartas_pretas_monte) - 1)];
            array_push($cartas_pretas_jogo, $carta);
        }

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
        $jogo = Jogo::find($req->input('id'));
        if ($req->input('my_id') != $jogo->id_jogador_criador) {
            return json_encode(["error" => "Você não é o host do jogo"]);
        }
        event(
            new MessageJogo($jogo->id, ['tp_message' => [3, 3], 'message' => 'Finalizando rodada...'])

        );
        $jogadores = json_decode($jogo->jogadores);
        $cartas_brancas_descartadas = json_decode($req->input('cartas_brancas_descartadas'));
        $jogador_ganhador = json_decode($req->input('jogador_ganhador'));
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

        if (count(json_decode($jogo->cartas_brancas_monte)) < count($jogadores) || count($cartas_pretas) < 3) {
            $this->FinalizarPartida($jogo->id);
            $jogador_vencedor = ["id_jogador" => 0, "pontuacao" => 0];
            foreach ($jogadores as $jogador) {
                if (count($jogador->pontuacao) > $jogador_vencedor["pontuacao"]) {
                    $jogador_vencedor = ["id_jogador" => $jogador->jogador, "pontuacao" => count($jogador->pontuacao)];
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
                $indJogador = array_search($id_jogador, array_column($jogadores, 'jogador'));
                $indCarta = array_search($carta, $jogadores[$indJogador]->cartas_brancas);
                array_splice($jogadores[$indJogador]->cartas_brancas, $indCarta, 1);
            }
        }

        $jogo->jogadores = json_encode($jogadores);

        //retirando do jogo a carta preta descartada
        array_slice($cartas_pretas, array_search($carta_preta_descartada, $cartas_pretas), 1);
        $jogo->cartas_pretas_monte = json_encode($cartas_pretas);
        $jogo->cartas_pretas_jogo = json_encode(array());
        $jogo->save();

        event(
            new MessageJogo($jogo->id, ['tp_message' => [3, 4], 'message' => 'Rodada Finalizada'])
        );
        $this->DistribuirCartas($jogo);
        return json_encode($jogo);
    }

    public function ProximaRodada(Request $req)
    {

        $jogo = Jogo::find($req->input('id_jogo'));
        event(
            new MessageJogo($jogo->id, ['tp_message' => [3, 1], 'message' => 'Iniciando proxima rodada'])
        );
        $jogo->rodada_jogo++;
        $jogo->save();

        $jogadores = json_decode($jogo->jogadores);
        event(
            new MessageJogo($jogo->id, ['tp_message' => [3, 2], 'message' => 'Rodada iniciada', 'jogador_leitor' => $jogadores[$jogo->rodada_jogo % count($jogadores)]->jogador])
        );
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
}
