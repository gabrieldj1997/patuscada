<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Rules\ReCAPTCHAv3;
use Illuminate\Support\Facades\Redirect;
use Exception;
use stdClass;

class LoginController extends Controller
{
    public function Index()
    {   
        if(!Auth::check())
        {
            return view('login.login');
        }
        return view('login.login', ["user" => Auth::user()]);
    }
    public function Register()
    {
        return view('login/register');
    }
    public function GetLogin(Request $req)
    {
        $nickname = $req->input('nickname');
        $password = $req->input('password');
        $remember = $req->input('remember');
        try {
            if ($this->Logar($nickname, $password, $remember)) {
                $req->session()->regenerate();
                return redirect('login/entrar');
            }
            return response()->json(['status' => 'Error', 'data' => 'User not found'],204);
        } catch (Exception $e) {
            return response($e, 200);
        }
    }
    public function RegisterLogin(Request $req)
    {
        $req->validate([
            'nickname' => 'required',
            'name' => 'required',
            'password' => 'required',
            'email' => 'required',
            'grecaptcha' => ['required', new ReCAPTCHAv3],
        ]);
        try {
            $login = new User();
            $login->name = $req->input('name');
            $login->nickname = $req->input('nickname');
            $login->password = Hash::make($req->input('password'));
            $login->email = $req->input('email');
            
            $login->save();

            self::Logar($req->input('nickname'), $req->input('password'), true);

            return response()->json(['status' => 'Login cadastro com sucesso!', 'user' => $login],200);
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
            $player->nickname = is_null($req->input('nickname')) ? $player->nickname : $req->input('nickname');
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

        return redirect('/login/entrar');
    }
    public function Logout(){
        Auth::logout();
        return redirect('/login/entrar');
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
    private function Logar($nickname, $password, $remember = false){
        if(Auth::attempt(['nickname' => $nickname, 'password' => $password], $remember)){
            return true;
        }
        return false;
    }
}
