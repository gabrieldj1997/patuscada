<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jogos extends Model
{
    static private $jogos = array();

    static public function getJogos()
    {
        return self::$jogos;
    }
    static public function addJogos($jogo)
    {
        array_push(self::$jogos, $jogo);
    }
    static public function removeJogos($id)
    {
        $key = array_search($id, array_column(self::$jogos, 'id'));
        if ($key !== false) {
            unset(self::$jogos[$key]);
            return true;
        }
        return false;
    }
}
