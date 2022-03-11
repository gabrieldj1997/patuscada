<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Rules\ReCAPTCHAv3;
use Illuminate\Support\Facades\Redirect;
use Exception;

class LoginController extends Controller
{
    public function Login()
    {
        return view('login');
    }
    public function Cadaster()
    {
        return view('cadaster');
    }
    public function GetLogin($id)
    {
        try {
            if (Player::where('id', $id)->exists()) {
                $player = Player::get()->where('id', $id)->frist();
                return response($player, 200);
            }

            if (Player::where('username', $id)->exists()) {
                $player = Player::get()->where('username', $id)->first();
                return response($player, 200);
            }
            return response('Jogador não cadastrado', 200);
        } catch (Exception $e) {
            return response($e, 200);
        }
    }
    public function RegisterLogin(Request $req)
    {
        $req->validate([
            'username' => 'required',
            'password' => 'required',
            'email' => 'required',
            'grecaptcha' => ['required', new ReCAPTCHAv3],
        ]);
        try {
            $login = new Player();
            $login->username = $req->input('username');
            $login->password = $req->input('password');
            $login->email = $req->input('email');
            $login->save();

            return response()->json(['status' => 'Login cadastro com sucesso!']);
        } catch (Exception $e) {
            return response()->json(['err' => $e->getMessage()], 202);
        }
    }
    public function UpdateLogin(Request $req, $id)
    {
        try {
            if (Player::where('id', $id)->exists()) {
                $player = Player::where('id', $id)->first();
            }

            if (Player::where('username', $id)->exists()) {
                $player = Player::where('username', $id)->first();
            }

            $player = Player::find($player->id);
            $player->username = is_null($req->input('username')) ? $player->username : $req->input('username');
            $player->password = is_null($req->input('password')) ? $player->password : $req->input('password');
            $player->email = is_null($req->input('email')) ? $player->username : $req->input('email');

            $player->save();

            return response()->json(['status' => 'Login atualizado com sucesso!']);
        } catch (Exception $e) {
            return response()->json(['Erro ao atualizar o login' => $e->getMessage()], 200);
        }
    }
    public function DeleteLogin($id)
    {
        try {
            if (Player::where('id', $id)->exists()) {
                $player = Player::where('id', $id);
            }

            if (Player::where('username', $id)->exists()) {
                $player = Player::where('username', $id);
            }
            $player->delete();
            return response()->json(['status' => 'Login deletado com sucesso!']);
        } catch (Exception $e) {
            return response()->json(['Erro ao deletar o login' => $e->getMessage()], 200);
        }
    }
    public function Truncate()
    {
        Player::truncate();

        return redirect('/login');
    }
}
