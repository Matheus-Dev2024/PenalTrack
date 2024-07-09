<?php

namespace App\Http\Controllers;

use App\Models\Entity\Comissao;
use App\Models\Entity\ProcessoAvaliacaoServidor;
use App\Models\Facade\ComissaoDB;
use App\Models\Regras\ComissaoRegras;
use App\Models\Regras\ServidorComissaoRegras;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComissaoController extends Controller
{
    public function grid()
    {
        $lista = ComissaoDB::grid();
        return response()->json($lista);
    }

    public function vincularServidoresGrid(Request $request)
    {
        $p = (object)$request->all();
        return ComissaoDB::vincularServidoresGrid($p);
    }

    public function alterar(Request $request)
    {
        try {
            ComissaoRegras::alterar($request);
            return response()->json(["mensagem" => "Presidente da comissão alterado com sucesso"]);

        } catch (Exception $ex) {
            return response()->json(["error" => $ex->getMessage()]);
        }
    }

    public function vincularServidoresStore(Request $request)
    {
        try {

            ServidorComissaoRegras::salvar($request);
            return response()->json(["mensagem" => "Servidor vinculado a uma comissão com sucesso!"]);

        } catch(Exception $ex) {
            return response()->json(["error" => $ex->getMessage()]);
        }
    }

    public function gridVisualizarComissao(Request $request)
    {
        $lista = ComissaoDB::gridVisualizarComissao($request->comissao_id);
        return response()->json($lista);
    }

    public function carregarParecer(Request $request)
    {
        $processo = ProcessoAvaliacaoServidor::where('id', $request->processo_id)->first();
        //verifica se o servidor ja foi avaliado em todos os períodos
        if($processo->fk_periodo == 6 && $processo->status == 2) {
            $lista = ComissaoDB::carregarParecer($request->processo_id);
            return response()->json($lista);
        } else {
            return response()->json(['error' => 'Servidor ainda em avaliação.']);
        }
        
    }

    public function salvarParecer(Request $request)
    {
        try {
            ComissaoRegras::salvarParecer($request);
            return response()->json(["mensagem" => "Parecer salvo com sucesso!"]);

        } catch(Exception $ex) {
            return response()->json(["error" => $ex->getMessage()]);
        }
    }

}
