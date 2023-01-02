<?php

namespace App\Http\Controllers;

use App\Models\Facade\ProcessoAvaliacaoDB;
use App\Models\Regras\ProcessoAvaliacaoRegras;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcessoAvaliacaoController extends Controller
{
    public function grid()
    {
        $lista = ProcessoAvaliacaoDB::listaProcessoAvaliacao();
        return response()->json($lista);
    }

    public function gridServidores(Request $request)
    {
        $lista = ProcessoAvaliacaoDB::listarServidoresGrid($request->ref_inicio, $request->ref_termino);
        return response()->json($lista);
    }
    public function servidoresGrid(Request $request)
    {

        $lista = ProcessoAvaliacaoDB::servidoresGrid($request->id_processo);
        return response()->json($lista);
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


    public function salvar(Request $request)
    {
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

            return response()->json(["mensagem" => "Período de Avaliação salvo com sucesso", "id_processo" => $processo->id]);
        } catch (Exception $ex) {
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
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }
    }
}
