<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioAvaliaServidorRequest;
use App\Models\Documentacao;
use App\Models\Entity\ProcessoAvaliacao;
use App\Models\Facade\DocumentacaoDB;
use App\Models\Facade\ProcessoAvaliacaoDB;
use App\Models\Facade\UsuarioAvaliaServidorDB;
use App\Models\Facade\UsuarioAvaliaServidoresDB;
use App\Models\Regras\ProcessoAvaliacaoRegras;
use App\Models\Regras\UsuarioAvaliaServidorRegras;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class ProcessoAvaliacaoController extends Controller
{
    public function grid()
    {
        $lista = ProcessoAvaliacaoDB::listaProcessoAvaliacao();
        return response()->json($lista);
    }

    public function gridServidores(Request $request)
    {
        $lista = ProcessoAvaliacaoDB::listarServidoresGrid($request->ref_inicio, $request->ref_termino, $request->dt_inicio_avaliacao, $request->dt_fim_avaliacao);
        return response()->json($lista);
    }
    public function servidoresGrid(Request $request)
    {
        $lista = ProcessoAvaliacaoDB::servidoresGrid($request->id_processo);
        return response()->json($lista);
    }

    public function exibirArquivo(Request $request)
    {
        $arquivo = Documentacao::find($request->id);
        $arquivo_resposta = stream_get_contents($arquivo->arquivo, -1);

        return response($arquivo_resposta, 200, [
            //'Content-Disposition' => 'attachment',
            //'Content-Type' => 'application/pdf',
            'Content-Type' => mime_content_type($arquivo->arquivo),
        ]);
    }

    public function pesquisarDescricao()
    {
        $lista = ProcessoAvaliacaoDB::pesquisarDescricao();
        return response()->json($lista);
    }

    public function ListaServidor()
    {
        $servidor = ProcessoAvaliacaoDB::listarServidor();
        return response()->json($servidor);
    }

    public function listaUsuarioAvaliaServidor(Request $request)
    {

        $p = (object)$request->validate([
            'usuario_id' => 'required',
        ]);
        return response()->json(UsuarioAvaliaServidorDB::grid($p));

    }

    public function salvarProcessoAvaliacaoServidor (Request $request)
    {
        DB::beginTransaction();
        try{
            $id_servidor = $request->id_servidor;
            $processo = ProcessoAvaliacao::find($request->processo_avaliacao);

            ProcessoAvaliacaoRegras::salvarProcessoAvaliacaoServidorIndividual($processo, $id_servidor);
            DB::commit();
            return response()->json(['message' => 'Servidor adicionado com sucesso.']);
        }
        catch (Exception $ex){
            DB::rollBack();
            return response()->json(['error' => 'Erro ao adicionar o servidor', 500]);
        }
    }

    public function salvarUsuarioAvaliaServidor (Request $request)
    {
        $p = (object) $request->all();

        DB::beginTransaction();
        try{
             if(!$request->processo_avaliacao){
                 return response()->json(['error' => 'O campo processo avaliação é obrigatório.']);
             }
             if(!$request->servidor_id){
                 return response()->json(['error' => 'O campo servidor é obrigatório.']);
             }

            UsuarioAvaliaServidorRegras::salvar($p);
            DB::commit();
            return response()->json(["mensagem" => "Servidor adicionado com sucesso."]);
        } catch(Exception $ex) {
            DB::rollback();
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }
    }

    public function salvar(Request $request)
    {
        $db = DB::beginTransaction();

        try {

            if (!$request->descricao) {
                return response()->json(['error' => 'O campo Descrição é obrigatório.']);
            }

            if (!$request->dt_inicio_avaliacao) {
                return response()->json(['error' => 'O campo inicio da avaliação é obrigatório.']);
            }
            if (!$request->dt_termino_avaliacao) {
                return response()->json(['error' => 'O campo término da avaliação é obrigatório.']);
            }
            if (!$request->dt_inicio_estagio) {
                return response()->json(['error' => 'O campo inicio do estagio é obrigatório.']);
            }
            if (!$request->ref_inicio) {
                return response()->json(['error' => 'O campo inicio da referência é obrigatório.']);
            }
            if (!$request->ref_termino) {
                return response()->json(['error' => 'O campo término da referência é obrigatório.']);
            }


            $processo = ProcessoAvaliacaoRegras::salvar($request);

            DB::commit();

            return response()->json(["mensagem" => "Período de Avaliação salvo com sucesso", "id_processo" => $processo->id]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde. ".$ex->getMessage()]);
        }
    }

    public function editar($id)
    {
        $dados = ProcessoAvaliacaoRegras::editar($id);
        return response()->json($dados);
    }

    public function alterar(Request $request)
    {
        try {
            ProcessoAvaliacaoRegras::alterar($request);
            return response()->json(["mensagem" => "Período de avaliacao alterado com sucesso"]);
        } catch (Exception $ex) {
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }
    }


    public function remover(Request $request)
    {
        try {
            ProcessoAvaliacaoRegras::remover($request->id_processo_avaliacao);
            return response()->json(["mensagem" => "Servidor removido com sucesso"]);
        } catch (Exception $ex) {
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }
    }

    public function excluir(Request $request)
    {
        try {
            ProcessoAvaliacaoRegras::excluir($request->id_periodo);
            return response()->json(["mensagem" => "Período de avaliacao removido com sucesso"]);
        } catch (Exception $ex) {
            return $ex;
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }
    }
}
