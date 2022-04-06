<?php

namespace App\Http\Controllers;

use App\Models\CartasPretas;
use App\Http\Requests\CartasPretasRequest;
use Illuminate\Http\Request;

class CartasPretasController extends Controller
{
    public function GetCartasPretas(){
        $cartasBrancas = CartasPretas::all();
        return $cartasBrancas;
    }
    public function GetCartaPreta($id){
        $CartaPreta = CartasPretas::find($id);
        return $CartaPreta;
    }
    public function RegisterCartaPreta(CartasPretasRequest $req){
        $CartaPreta = new CartasPretas();
        $CartaPreta->texto = $req->texto;
        $CartaPreta->save();
        return $CartaPreta;
    }
    public function DeleteCartaPreta($id){
        $CartaPreta = CartasPretas::find($id);
        $CartaPreta->delete();
        return $CartaPreta;
    }
    public function AlterCartaPreta(Request $req, $id){
        $cartaPreta = CartasPretas::find($id);
        $cartaPreta->texto = $req->texto;
        $cartaPreta->save();
        return $cartaPreta;
    }
}
