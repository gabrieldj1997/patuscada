<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Rules\ReCAPTCHAv3;
use Illuminate\Support\Facades\Redirect;
use Exception;
use stdClass;
use Symfony\Component\HttpFoundation\RequestStack;

class LoginController extends Controller
{
    //Front-end
    public function Index()
    {
        return view('login.login');
    }
    public function Register()
    {
        return view('login/register');
    }
    //Back-end
    public function AutenticateLogin(Request $req)
    {
        try {
            if (Auth::attempt(['nickname' => $req->input('nickname'), 'password' => $req->input('password')], true)) {
                return view('login.login', ['message' => 'Login efetuado com sucesso.']);
            }
            return view('login.login', ['error' => 'Usuario ou senha invalidos.']);
        } catch (Exception $e) {
            return view('login.login', ['error' => 'Erro no servidor. ' . $e->getMessage()]);
        }
    }
    public function RegisterLogin(LoginFormRequest $req)
    {
        try {
            $login = new User();
            $login->name = $req->input('name');
            $login->nickname = $req->input('nickname');
            $login->password = Hash::make($req->input('password'));
            $login->email = $req->input('email');
            $login->save();

            if (Auth::attempt(['nickname' => $req->input('nickname'), 'password' => $req->input('password')], true)) {
                return redirect()->route('login.index')->with('message', 'Usuario cadastrado com sucesso.');
            }
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['errors' => 'Erro no servidor, tente novamente mais tarde.', 'message' => json_encode($e)], 500);
        }
    }
    public function UsersOnline(Request $req)
    {
        try {
            $users = User::where('status', 'online')->get();
            return response($users, 200);
        } catch (Exception $e) {
            return response($e->getMessage(),200);
        }
    }
    public function UpdateLogin(LoginFormRequest $req)
    {
        try {
            if (User::where('nickname', $req->input('nickname'))->exists()) {
                $player = User::where('nickname', $req)->first();
                if (Hash::make($req->input('password')) == $player->password) {
                    $player = User::find($player->id);
                    $player->nickname = is_null($req->input('nickname')) ? $player->nickname : $req->input('nickname');
                    $player->password = is_null($req->input('password')) ? $player->password : $req->input('password');
                    $player->email = is_null($req->input('email')) ? $player->nickname : $req->input('email');

                    $player->save();
                    return response()->json(['status' => 'Success', 'message' => 'Usuario alterado com sucesso', 'user' => $player], 200);
                }
                return response()->json(['status' => 'Error', 'message' => 'Senha incorreta.', 'user' => $req->input('nickname')], 204);
            }
            return response()->json(['status' => 'Error', 'message' => 'Usario não encontrado no sistema'], 204);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erro ao atualizar o login', 'message' => $e->getMessage()], 500);
        }
    }
    public function DeleteLogin(Request $req, $id)
    {
        if (Auth::check()) {
            if (Auth::user()->id != $id) {
                return redirect()->route('login.index')->with('error', 'Não é possivel deletar outro usuario.');
            }
            try {
                $player = User::find($id);
                if (isset($player) && Hash::check($req->input('password'), $player->password)) {
                    Auth::logout();
                    $player->delete();
                    return redirect()->route('login.index')->with('message', 'Usuario deletado com sucesso.');
                } else {
                    return redirect()->route('login.index')->with('error', 'Senha invalida.');
                }
            } catch (Exception $e) {
                return redirect()->route('login.index')->with('error', 'Error no servidor. ' . $e->getMessage());
            }
        } else {

            $player = User::find()->where('id', $id)->where('password', Hash::make($req->input('password')))->first();
            if (isset($player)) {
                $player->delete();
                return redirect()->route('login.index')->with('message', 'Usuario deletado com sucesso.');
            }
            return redirect()->route('login.index')->with('error', 'Usuario ou senha invalidos, usuario de id ' . $id . ' não encontrado.');
        }
    }
    public function Truncate()
    {
        Auth::logout();
        User::truncate();

        return redirect('/login/entrar');
    }
    public function Logout()
    {
        Auth::logout();
        return redirect('/login/entrar');
    }
    public function Captcha(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'g-recaptcha-response' => ['required', new ReCAPTCHAv3],
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'Error', 'message' => 'Captcha inválido.'], 202);
        }
        return response()->json(['status' => 'Success', 'message' => 'Captcha validado.'], 200);
    }
}
