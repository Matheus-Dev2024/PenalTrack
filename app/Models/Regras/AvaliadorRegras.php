<?php

namespace App\Models\Regras;

use App\Models\Entity\Avaliador;
use App\Models\Entity\UsuarioAvaliaServidores;
use App\Models\Entity\UsuarioSistema;
use App\Models\Entity\UsuarioAvaliaUnidades;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;



class AvaliadorRegras
{

    public static function salvar($dados)
    {

        $senha2 = password_hash($dados->senha2, PASSWORD_BCRYPT);

        // Cadastra o usuário
        $avaliador = Avaliador::create([
            'cpf' => $dados->cpf,
            'nome' => $dados->nome,
            'nascimento' => $dados->nascimento,
            'email' => $dados->email,
            'senha' => " ",
            'senha2' => $senha2,
            'dt_cadastro' => date('Y-m-d H:i:s')
        ]);
       
        // Cria as unidades para o determinado usuário
        foreach ($dados->usuario_unidades as $unidade) {
            UsuarioAvaliaUnidades::create([
                'usuario_id' => $avaliador->id,
                'unidade_id' => $unidade['id']
            ]);
        }

        //Adiciona o usuário no sistema estágio probatório (56)
        UsuarioSistema::create([
            'sistema_id' => 56,
            'usuario_id' => $avaliador->id
        ]);

        return $avaliador;
    }

    public static function adicionarUnidades($dados)
    {
        // Adicionar a unidade para o determinado avaliador
        return $usuarioAvaliaUnidade = UsuarioAvaliaUnidades::create([
            'usuario_id' => $dados->id,
            'unidade_id' => $dados->id_unidade
        ]);
    }

    

    public static function alterar($dados)
    {
        $avaliador = Avaliador::find($dados->id);
        $avaliador->cpf = $dados->cpf;
        $avaliador->nome = $dados->nome;
        $avaliador->nascimento = $dados->nascimento;
        $avaliador->email = $dados->email;
        // $avaliador->senha = password_hash($dados->senha, PASSWORD_BCRYPT);
        if ($dados->senha2) {
            $senha2 = password_hash($dados->senha2, PASSWORD_BCRYPT);
            $avaliador->senha2 = $senha2;
        }

        $avaliador->save();
    }

    public static function removerUnidades($id)
    {
        if (UsuarioAvaliaUnidades::where('id', $id)->exists()) {
            $unidade = UsuarioAvaliaUnidades::find($id);
            $unidade->delete();
            return response()->json(['mensagem' => 'Unidade excluída com sucesso']);
        } else {
            return response()->json(['erro' => 'Unidade não encontrada']);
        }
    }

    public static function removerAvaliador($id)
    {
        $avaliadorUnidades = UsuarioAvaliaUnidades::where('usuario_id', $id)->get();

        $avaliadorUnidades->each(function ($avaliadorUnidade) {
            $avaliadorUnidade->delete();
        });

        UsuarioSistema::where(['sistema_id' => 56, 'usuario_id' => $id])->delete();
    }
    
}
