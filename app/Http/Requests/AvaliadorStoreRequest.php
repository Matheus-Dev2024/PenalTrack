<?php

namespace App\Http\Requests;

use App\Models\Entity\Avaliador;
use App\Models\Entity\UsuarioAvaliaUnidades;
use App\Models\Entity\UsuarioSistema;
use Illuminate\Foundation\Http\FormRequest;


class AvaliadorStoreRequest extends FormRequest
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
            'cpf' => 'required',
            'nome' => 'required',
            // 'email' => ['required', 'email', 'max:255', 'unique:conexao_seguranca.seguranca.usuario'],
            'email' => 'required|email',
            'nascimento' => 'required|date_format:Y-m-d',
            'senha' => 'required|same:senha2',
            'senha2' => 'required',
            'usuario_unidades' => 'required|array|min:1'
        ];
    }

    public function messages()
    {
        return [
            'cpf.required' => 'O campo CPF é obrigatório.',
            'nome.required' => 'O campo Nome é obrigatório.',
            'email.required' => 'O campo Email é obrigatório.',
            'email.email' => 'O campo Email tem que ser um email válido.',
            // 'email.unique' => 'O Email já está em uso.',
            'nascimento.required' => 'O campo Data de nascimento é obrigatório.',
            'nascimento.date_format' => 'O campo Data de nascimento deve estar no formato dd/mm/aaaa.',
            'senha.required' => 'O campo Senha é obrigatório.',
            'senha.same' => 'As senhas não correspondem.',
            'senha.min' => 'A senha deve ter no mínimo :min caracteres.',
            'senha2.required' => 'O campo confirmar senha é obrigatório.',
            'usuario_unidades.required' => 'Por favor, adicione pelo menos uma unidade.',
            //'usuario_unidades.required => ''


        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $falhas = $validator->failed();

            // Se houver qualquer erro na validação básica, então retorna os erros
            if (!empty($falhas)) {
                return;
            }

            $usuario = Avaliador::where('email', request()->email)->first();

            if ($usuario) {
                $usuarioSistema = UsuarioSistema::where('sistema_id', "56")->where('usuario_id', $usuario->id)->first();

                if ($usuarioSistema) {
                    $validator->errors()->add('email', "O email já está sendo utilizado por outro avaliador!");
                }
            }

            // if ($validator->fails()) {
            //     $errors = $validator->errors()->all();
            //     return response()->json(['errors' => $errors], 401);
            // }
        });
    }
}
