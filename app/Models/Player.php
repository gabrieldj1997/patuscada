<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $table = 'tb_player';

    protected $fillable = [
        'nickname',
        'password',
        'email',
        'pontuacao'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
