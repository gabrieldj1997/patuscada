<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jogo extends Model
{
    protected $table = 'tb_jogo';

    protected $fillable = [
        'codigo', 
        'nome_jogo',
        'id_estado_jogo',
        'rodada',
        'players'];
}
