<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jogo extends Model
{
    protected $table = 'tb_jogos';

    protected $fillable = [
        'codigo_jogo',
        'id_jogador_criador',
        'jogadores',
        'cartas_brancas_monte',
        'cartas_pretas_monte',
        'cartas_pretas_jogo',
        'rodada_jogo',
        'estado_jogo',
    ];
}
