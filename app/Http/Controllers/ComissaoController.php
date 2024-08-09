<?php

namespace App\Http\Controllers;

use App\Models\Entity\Comissao;
use App\Models\Entity\ProcessoAvaliacaoServidor;
use App\Models\Facade\ComissaoDB;
use App\Models\Regras\ComissaoRegras;
use App\Models\Regras\ServidorComissaoRegras;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ComissaoController extends Controller
{
    public function grid()
    {
        $lista = ComissaoDB::grid();
        return $lista;
    }

    public function alterar(Request $request)
    {
        try {
            ComissaoRegras::alterar($request);
            return response()->json(["mensagem" => "Comissão alterada com sucesso"]);

        } catch (Exception $ex) {
            return response()->json(["error" => $ex->getMessage(), 400]);
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
            $info = ComissaoDB::montarTextoParecer($request->processo_id);
            return response()->json($info);
            // $lista = ComissaoDB::carregarParecer($request->processo_id);
            // return response()->json($lista);
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

    public function autoCompletePresidenteComissao(Request $request) :Collection
    {
        $p = (object)$request->validate([
           'nome' => 'required'
        ]);
        return ComissaoDB::servidorPolicial($p);
    }

    public function getAllInfoComissao()
    {
        $info = [];

        $info['tipo_comissao'] = ComissaoDB::comboCargoComissao();

        return $info;
    }

    public static function antecedentesServidor(Request $request) 
    {
        $id_servidor = $request->id_servidor;

        // Define a consulta com o parâmetro do id_servidor
        $antecedentes = DB::select("SELECT * FROM srh.antecedentes_servidor() WHERE id_servidor = :id_servidor", ['id_servidor' => $id_servidor]);
    
        return response()->json($antecedentes);
    }
    
}
