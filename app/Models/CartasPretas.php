<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartasPretas extends Model
{
    protected $table = 'tb_cartas_pretas';

    protected $fillable = [
        'texto', 
        'id_pack',
        'pontos'];
}
