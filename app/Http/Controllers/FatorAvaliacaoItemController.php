<?php

namespace App\Http\Controllers;

use App\Models\Facade\FatorAvaliacaoItemDB;
use App\Models\Regras\FatorAvaliacaoItemRegras;
use Exception;
use Illuminate\Http\Request;

class FatorAvaliacaoItemController extends Controller
{

    public function grid () 
    {
        $lista = FatorAvaliacaoItemDB::listarFatorAvaliacao();
        return response()->json($lista);
    }
    
    public function salvar(Request $request)
    {
        try {
            
             if(!$request->fator_avaliacao) {
                return response()->json(['error' => 'O campo fator de avaliacao é obrigatório.']);
            }
            
            if(!$request->pergunta) {
                return response()->json(['error' => 'O campo pergunta é obrigatório.']);
            }
            
            FatorAvaliacaoItemRegras::salvar($request);
            

            return response()->json(["mensagem" => "Fator de Avaliação salvo com sucesso"]);

        } catch(Exception $ex) {
            
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }
        
    }

    public function alterar(Request $request)
    {
        try {
            FatorAvaliacaoItemRegras::alterar($request);
            return response()->json(["mensagem" => "Fator de Avaliação alterado com sucesso"]);

        } catch(Exception $ex) {
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }
        
    }

    public function excluir(Request $request)
    {
        try {
            FatorAvaliacaoItemRegras::excluir($request->id);
            return response()->json(["mensagem" => "Fator de Avaliação excluido com sucesso"]);

        } catch(Exception $ex) {
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }
        
    }

}
