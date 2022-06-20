<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ReCAPTCHAv3;

class LoginFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'nickname' => 'required|string|max:15|unique:users',
            'email' => 'required|max:50|unique:users',
            'password' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório',
            'nickname.required' => 'O campo nickname é obrigatório',
            'nickname.unique' => 'Este nickname ja esta em uso',
            'email.required' => 'O campo email é obrigatório',
            'password.required' => 'O campo senha é obrigatório',
            'nickame.unique' => 'O nickname já está em uso',
            'email.unique' => 'O email já está em uso'
        ];
    }
}
