<?php

namespace App\Http\Controllers;

use App\Models\Players;
use Illuminate\Http\Request;

class Login extends Controller
{
    public function GetAllPlayers(){
        return 'teste';
    }

    public function CreatePlayer(Request $req){
        $login = new Players();
        $login->name = $req->name;
        $login->password = $req->password;
        $login->save();
    }

    public function GetPlayer(Request $req, $id){

    }

    public function UpdatePlayer(Request $req, $id){

    }

    public function DeletePlayer($id){

    }
}
