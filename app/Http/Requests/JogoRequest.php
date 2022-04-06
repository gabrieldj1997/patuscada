<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JogoRequest extends FormRequest
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
            'codigo' => 'required|string|max:5|unique:tb_jogos',
            'nome_jogo' => 'required|string|max:15'
        ];
    }
    public function messages()
    {
        return [
            'codigo.required' => 'O campo nome é obrigatório',
            'codigo.unique' => 'Já existe um jogo com esse código',
            'nome_jogo.required' => 'O campo nickname é obrigatório'
        ];
    }
}
