<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Rules\ReCAPTCHAv3;
use Illuminate\Support\Facades\Redirect;
use Exception;

class LoginController extends Controller
{
    public function Login()
    {
        return view('login.login');
    }
    public function Register()
    {
        return view('login/register');
    }
    public function GetLogin(Request $req)
    {
        return var_dump($req);
        if (Auth::attempt(['email' => $req->email, 'password' => $req->password]))
        {
            return redirect()->intended('dashboard');
        }
        // try {
        //     if (User::where('id', $id)->exists()) {
        //         $player = User::get()->where('id', $id)->frist();
        //         return response()->json(['status' => 'Success', 'data' => $player],200);
        //     }

        //     if (User::where('username', $id)->exists()) {
        //         $player = User::get()->where('username', $id)->first();
        //         return response()->json(['status' => 'Success', 'data' => $player],200);
        //     }
        //     return response()->json(['status' => 'Error', 'data' => 'User not found'],204);
        // } catch (Exception $e) {
        //     return response($e, 500);
        // }
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
            $login = new User();
            $login->name = 'teste';
            $login->nickname = $req->input('username');
            $login->password = Hash::make($req->input('password'));
            $login->email = $req->input('email');
            

            $login->save();

            return response()->json(['status' => 'Login cadastro com sucesso!']);
        } catch (Exception $e) {
            return response()->json(['err' => $e->getMessage()], 200);
        }
    }
    public function UpdateLogin(Request $req, $id)
    {
        try {
            if (User::where('id', $id)->exists()) {
                $player = User::where('id', $id)->first();
            }

            if (User::where('nickname', $id)->exists()) {
                $player = User::where('nickname', $id)->first();
            }

            $player = User::find($player->id);
            $player->nickname = is_null($req->input('username')) ? $player->nickname : $req->input('username');
            $player->password = is_null($req->input('password')) ? $player->password : $req->input('password');
            $player->email = is_null($req->input('email')) ? $player->nickname : $req->input('email');

            $player->save();

            return response()->json(['status' => 'Login atualizado com sucesso!', 'data' => $player],200);
        } catch (Exception $e) {
            return response()->json(['Erro ao atualizar o login' => $e->getMessage()], 500);
        }
    }
    public function DeleteLogin($id)
    {
        try {
            if (User::where('id', $id)->exists()) {
                $player = User::where('id', $id);
            }

            if (User::where('nickname', $id)->exists()) {
                $player = User::where('nickname', $id);
            }
            $player->delete();
            return response()->json(['status' => 'Login deletado com sucesso!', 'data' => $player],200);
        } catch (Exception $e) {
            return response()->json(['Erro ao deletar o login' => $e->getMessage()], 500);
        }
    }
    public function Truncate()
    {
        User::truncate();

        return redirect('/login');
    }
    /* Model Response Login
        {
            status: 'description of status of request',
            data: {
                id: 'id of player',
                nickname: 'nickname of player',
                email: 'email of player',
                password: 'password of player'
            }
        }
    */
}
