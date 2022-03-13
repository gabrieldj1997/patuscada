<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartasBrancas extends Model
{
    protected $table = 'tb_cartas_brancas';

    protected $fillable = [
        'texto', 
        'id_pack',
        'pontos'];
}
