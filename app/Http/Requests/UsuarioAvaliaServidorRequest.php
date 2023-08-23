<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioAvaliaServidorRequest extends FormRequest
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
           'processo_avaliacao' => 'required',
           'usuario_id' => 'required',
           'servidor_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'processo_avaliacao.required' => 'O campo processo de avaliação é obrigatório',
            'servidor_id.required' => 'O campo servidor é obrigatório',
            'usuario_id.required' => 'Usuário não encontrado',
        ];
    }

}