<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rodadas extends Model
{
    protected $table = 'tb_rodadas';

    protected $fillable = [
        'id_jogo', 
        'rodada', 
        'id_estado_rodada',
        'id_jogador_mestre',
        'jogadores',//id,nome,pontuacao
        'cartas_brancas_jogador',//id_jogadore,id_carta
        'cartas_brancas_monte',
        'cartas_brancas_descartardas',
        'cartas_pretas_jogador',//id_jogadore,id_carta
        'cartas_pretas_monte',
        'cartas_pretas_descartardas',
        'cartas_brancas_selecionadas',
        'carta_preta_selecionadas'
        ];
}
