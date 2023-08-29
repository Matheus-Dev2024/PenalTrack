<?php

namespace App\Http\Controllers;

use App\Models\Entity\ArquivoAvaliacaoServidor;
use App\Models\Entity\FatorAvaliacao;
use App\Models\Facade\AvaliacaoDB;
use App\Models\Regras\AvaliacaoServidorRegras;
use App\Models\Regras\ProcessoAvaliacaoRegras;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AvaliacaoController extends Controller
{
    public function formulario()
    {
        $formulario = FatorAvaliacao::all();

        foreach($formulario as $fator) {
            $fator->itens;
        }

        return response()->json($formulario);
    }

    public function info(Request $request)
    {
        $p = (object) $request->validate([
            'processo_id' => 'required',
            'servidor_id' => 'required'
        ]);
        return AvaliacaoServidorRegras::info($p);
    }

    public function store(Request $request)
    {

        //Inicia o Database Transaction
        DB::beginTransaction();
        try {
            $params = (object) $request->all();
            AvaliacaoServidorRegras::adicionarNotas($params);
            ProcessoAvaliacaoRegras::atualizaSituacaoServidor($params);
            DB::commit();
            return response()->json(['message' => 'Avaliação enviada com sucesso.']);
        } catch(Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => 'Opa, algo aconteceu. '.$ex->getMessage()], 500);
        }
    }

    public function getServidoresAvaliacaoCorrente(Request $request)
    {
        $p = (object) $request->all();
        return AvaliacaoDB::getListaServidoresDoProcessoAvaliacao($p);
    }


    // Retorna os arquivos de uma Avaliação
    public function GridArquivos(Request $request){
        $p = (object) $request->validate([
            'servidor_id' => 'required',
            'processo_avaliacao_id' => 'required'
        ]);
        return response()->json(AvaliacaoServidorRegras::gridArquivos($p));
    }

    // Faz o upload de um arquivo para o banco
    public function uploadArquivo(Request $request){
        $p = (object)$request->all();
        DB::beginTransaction();
        try{
            AvaliacaoServidorRegras::uploadArquivo($request);
            DB::commit();
            return response()->json([
                'message' => 'Arquivo armazenado com sucesso'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage()], 500);
            } else {
                return response()->json(['message' => 'Falha ao gravar arquivos'], 500);
            }
        }
    }

    // Exclui um arquivo específico através do ID
    public function ExcluirArquivo(ArquivoAvaliacaoServidor $arquivo){
        DB::beginTransaction();
        try{
            AvaliacaoServidorRegras::excluirArquivo($arquivo);
            DB::commit();
            return response()->json([
                'message' => "Arquivo excluído com sucesso!"
            ]);
        }  catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage()], 500);
            } else {
                return response()->json(['message' => 'Falha ao excluir arquivo'], 500);
            }
        }
    }

    public function combo()
    {
        $comboProcesso = AvaliacaoDB::combo();
        return compact('comboProcesso');
    }


    //retorna um arquivo para ser baixado
    public function exibirArquivo(Request $request){
        return AvaliacaoServidorRegras::exibirArquivo($request);
    }

}
