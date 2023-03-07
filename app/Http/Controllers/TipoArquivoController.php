<?php

namespace App\Http\Controllers;

use App\Models\Entity\TipoArquivo;
use App\Models\Regras\FatorAvaliacaoRegras;
use App\Models\TipoArquivoRegras;
use Exception;
use Illuminate\Http\Request;

class TipoArquivoController extends Controller
{

    public function grid()
    {
        return TipoArquivoRegras::grid();
    }

    public function salvar(Request $request)
    {
        try {
            if(!$request->fator_avaliacao) {
                return response()->json(['error' => 'O campo fator de avaliacao é obrigatório.']);
            }

            FatorAvaliacaoRegras::salvar($request);
            return response()->json(["mensagem" => "Fator de Avaliação salvo com sucesso"]);

        } catch(Exception $ex) {
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }

    }

    public function alterar(Request $request)
    {
        try {
            FatorAvaliacaoRegras::alterar($request);
            return response()->json(["mensagem" => "Fator de Avaliação alterado com sucesso"]);

        } catch(Exception $ex) {
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }

    }

    public function excluir(Request $request)
    {
        try {
            FatorAvaliacaoRegras::excluir($request->id);
            return response()->json(["mensagem" => "Fator de Avaliação excluido com sucesso"]);

        } catch(Exception $ex) {
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }

    }

}
