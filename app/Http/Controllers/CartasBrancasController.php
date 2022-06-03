<?php

namespace App\Http\Controllers;

use App\Models\CartasBrancas;
use App\Http\Requests\CartasBrancasRequest;
use Illuminate\Http\Request;

class CartasBrancasController extends Controller
{
    
    public function GetCartasBrancas(){
        $cartasBrancas = CartasBrancas::all();
        return $cartasBrancas;
    }
    public function GetCartaBranca($id){
        $cartaBranca = CartasBrancas::find($id);
        return $cartaBranca;
    }
    public function RegisterCartaBranca(CartasBrancasRequest $req){
        $cartaBranca = new CartasBrancas();
        $cartaBranca->texto = $req->texto;
        $cartaBranca->save();
        return $cartaBranca;
    }
    public function DeleteCartaBranca($id){
        $cartaBranca = CartasBrancas::find($id);
        $cartaBranca->delete();
        return $cartaBranca;
    }
    public function AlterCartaBranca(Request $req, $id){
        $cartaBranca = CartasBrancas::find($id);
        $cartaBranca->texto = $req->texto;
        $cartaBranca->save();
        return $cartaBranca;
    }
}
