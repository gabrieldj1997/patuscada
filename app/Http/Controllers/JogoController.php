<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jogo;

class JogoController extends Controller
{
    private $jogos;

    function __construct(){
        $this->jogos = array();
    }

    function index(){
        foreach($this->jogos as $jogo){
            var_dump($jogo);
        }
    }
}
