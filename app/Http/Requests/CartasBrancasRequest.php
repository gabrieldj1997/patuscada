<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartasBrancasRequest extends FormRequest
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
            'texto' => 'required|string|max:500'
        ];
    }
    public function messages()
    {
        return [
            'texto.required' => 'O campo nome é obrigatório'
        ];
    }
}
