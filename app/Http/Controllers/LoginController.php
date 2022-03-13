<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Rules\ReCAPTCHAv3;
use Illuminate\Support\Facades\Redirect;
use Exception;

class LoginController extends Controller
{
    private static $qtdJogadoreLogados;

    public function Login()
    {
        return view('login/login', ['qtdJogadoresLogados' => self::$qtdJogadoreLogados]);
    }
    public function Register()
    {
        return view('login/register');
    }
    public function GetLogin($id)
    {
        try {
            if (Player::where('id', $id)->exists()) {
                $player = Player::get()->where('id', $id)->frist();
                $this->qtdJogadoreLogados++;
                return response()->json(['status' => 'Success', 'data' => $player],200);
            }

            if (Player::where('username', $id)->exists()) {
                $this->AddJogadorOnline();
                return 'true';
                $player = Player::get()->where('username', $id)->first();
                return response()->json(['status' => 'Success', 'data' => $player],200);
            }
            return response()->json(['status' => 'Error', 'data' => 'Player not found'],204);
        } catch (Exception $e) {
            return response($e, 500);
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
            return response()->json(['err' => $e->getMessage()], 500);
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

            return response()->json(['status' => 'Login atualizado com sucesso!', 'data' => $player],200);
        } catch (Exception $e) {
            return response()->json(['Erro ao atualizar o login' => $e->getMessage()], 500);
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
            return response()->json(['status' => 'Login deletado com sucesso!', 'data' => $player],200);
        } catch (Exception $e) {
            return response()->json(['Erro ao deletar o login' => $e->getMessage()], 500);
        }
    }
    public function Truncate()
    {
        Player::truncate();

        return redirect('/login');
    }

    private function AddJogadorOnline(){
        self::$qtdJogadoreLogados++;
    }

    /*
     Model Response Login
        {
            status: 'description of status of request',
            data: {
                id: 'id of player',
                username: 'username of player',
                email: 'email of player',
                password: 'password of player'
            }
        }
    */
}
