<?php

namespace App\Models\Regras;

use App\Models\Entity\Avaliador;
use App\Models\Entity\Grupo;
use App\Models\Entity\GrupoSrh;
use App\Models\Entity\UsuarioCadastroAvaliador;
use App\Models\Entity\UsuarioAvaliaServidores;
use App\Models\Entity\UsuarioAvaliaUnidades;
use App\Models\Entity\UsuarioRisp;
use App\Models\Entity\UsuarioSistema;
use Illuminate\Http\JsonResponse;
use stdClass;
use PoliciaCivil\Seguranca\Models\Entity\SegGrupo;
use PoliciaCivil\Seguranca\Models\Entity\Usuario;

class AvaliadorRegras
{

    public static function salvar(stdClass $dados): JsonResponse
    {
        $senha2 = password_hash($dados->senha2, PASSWORD_BCRYPT);
        $dados->senha2 = $senha2;

        // Limpa o CPF para poder pesquisar se o usuário já existe.
        $dados->cpf = str_replace(['.', '-'], '', $dados->cpf);

        // Cria um avaliador em branco para guardar os dados
        $avaliadorNovo = new Avaliador();

        // Pesquisa todos os possiveis usuários com o CPF informado
        $possiveisAvaliadores = Avaliador::where('cpf', $dados->cpf)
            ->where('excluido', false)
            ->orderBy('id')
            ->get();

        // Se houver mais de um usuário com o CPF informado, desativa os mais novos, mantendo apenas o usuário mais antigo
        if (count($possiveisAvaliadores->toArray()) > 0) {
            foreach ($possiveisAvaliadores as $key => $avaliador) {
                //Atualiza os dados do usuário mais atingo
                if ($key == 0) {
                    $avaliadorNovo = self::atualizarDadosAvaliador($avaliador, $dados);
                    $avaliador->save();
                }
                //desabilita os usuários mais novos
                // if ($key > 0) {
                //     $avaliador->excluido = true;
                //     $avaliador->save();
                // }
            }
        } // Se não localizar usuário com o cpf informado, cria um usuário.
        else {
            $avaliadorNovo = self::atualizarDadosAvaliador($avaliadorNovo, $dados);
            $avaliadorNovo->dt_cadastro = date('Y-m-d H:i:s');
            $avaliadorNovo->cpf = $dados->cpf;
            $avaliadorNovo->save();
        }

        // Cria as unidades para o avaliador novo
        foreach ($dados->usuario_unidades as $unidade) {
            UsuarioAvaliaUnidades::create([
                'usuario_id' => $avaliadorNovo->id,
                'unidade_id' => $unidade['id']
            ]);
        }

        
        //registra o usuário cadastrado na tabela grupo que relaciona o perfil do novo usuário, no sistema e-probatório é necessário para carregar os menus
        // perfil criado como perfil eprobatorio no ambiente de desenvolvimento (1)
        Grupo::create([
            'usuario_id' => $avaliadorNovo->id,
            'perfil_id' => 1
        ]);

        // Verifica se esse usuario ja possui permissao para o sistema eprobatorio
        $usuarioSistema = UsuarioSistema::where('sistema_id', 56)->where('usuario_id', $avaliadorNovo->id)->first();
        if(isset($usuarioSistema)){
            return response()->json([
                "usuario" => $usuarioSistema->id,
                "errors" => (object)['avaliador' => 'Este Avaliador já encontra-se cadastrado!']
            ], 409);
        }
        
        //verifica se o perfil do usuario logado, se for de superintendente(41), vai salvar a risp;
        //se o perfil for de diretor(42), vai salvar a diretoria(fk_unidade);        
        $perfisUsuarioLogado = GrupoSrh::where('fk_usuario', $dados->usuario_cad)->get();
        foreach ($perfisUsuarioLogado as $perfil) {
            // 41 é o perfil de superintendente
            if($perfil->fk_perfil == 41) {
                $risp_usuario_logado = UsuarioRisp::where('fk_usuario', $dados->usuario_cad)->first();
                UsuarioCadastroAvaliador::create([
                    'usuario_cadastrou' => $dados->usuario_cad,
                    'usuario_cadastrado' => $avaliadorNovo->id,
                    'fk_risp' => $risp_usuario_logado->fk_risp,
                    //'fk_diretoria' => $diretoria_usuario_logado->fk_unidade
                ]);
            // 42 é o perfil de diretor
            } elseif($perfil->fk_perfil == 42) {
                $diretoria_usuario_logado = Usuario::where('id', $dados->usuario_cad)->first();
                $risp_usuario_logado = UsuarioRisp::where('fk_usuario', $dados->usuario_cad)->first();
                UsuarioCadastroAvaliador::create([
                    'usuario_cadastrou' => $dados->usuario_cad,
                    'usuario_cadastrado' => $avaliadorNovo->id,
                    'fk_risp' => $risp_usuario_logado->fk_risp,
                    'fk_diretoria' => $diretoria_usuario_logado->fk_unidade
                ]);
            } 
        };

        //Adiciona o usuário no sistema estágio probatório (56)
        UsuarioSistema::create([
            'sistema_id' => 56,
            'usuario_id' => $avaliadorNovo->id
        ]);

        return response()->json(["id" => $avaliadorNovo->id, "mensagem" => "Avaliador cadastrado com sucesso!"], 200);
    }

    private static function atualizarDadosAvaliador(Avaliador $avaliador, stdClass $dados): Avaliador
    {
        // Atualiza os dados do avaliador
        $avaliador->nome = $dados->nome;
        $avaliador->nascimento = $dados->nascimento;
        $avaliador->email = $dados->email;
        //$avaliador->senha = "";
        $avaliador->senha2 = $dados->senha2;
        return $avaliador;
        // Atribui o perfil a esse usuário
        $novoGrupo = SegGrupo::create([
            'usuario_id' => $avaliador->id,
            'perfil_id' => '1' //Utiliza o perfil root, pois o segurança não funciona. Deveria utilizar o 2, que é 'avaliador'
        ]);
        $novoGrupo->save();

        return response()->json(["id" => $avaliador->id, "message" => "Avaliador cadastrado com sucesso!"]);
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

    // Esta funcao sera chamada quando for necessario alterar os dados de um avaliador.

    public static function removerAvaliador($id)
    {
        $avaliadorUnidades = UsuarioAvaliaUnidades::where('usuario_id', $id)->get();
        $avaliadorServidores = UsuarioAvaliaServidores::where('usuario_id', $id)->get();
        $avaliadorGrupo = SegGrupo::where('usuario_id', $id)->get();
        $avaliadorIntermediario = UsuarioCadastroAvaliador::where('usuario_cadastrado', $id)->get();

        $avaliadorGrupo->each(function ($avaGrupo) {
            $avaGrupo->delete();
        });
        $avaliadorIntermediario->each(function ($avaInter) {
            $avaInter->delete();
        });

        $avaliadorServidores->each(function ($avaliadorServidore) {
            $avaliadorServidore->delete();
        });

        $avaliadorUnidades->each(function ($avaliadorUnidade) {
            $avaliadorUnidade->delete();
        });

        UsuarioSistema::where(['sistema_id' => 56, 'usuario_id' => $id])->delete();
    }

}
