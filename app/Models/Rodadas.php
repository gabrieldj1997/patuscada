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
        'cartas_jogador',//id_jogadore,id_carta
        'cartas_monte',
        'cartas_descartardas'
        ];
}
