<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JogadorCartas extends Model
{
    protected $table = 'tb_jogador_cartas';

    protected $fillable = [
        'id_jogo',
        'id_jogador',
        'pontuacao',
        'cartas',
    ];
}
