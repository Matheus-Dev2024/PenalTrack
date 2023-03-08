<?php

namespace App\Http\Controllers;

use App\Models\Entity\TipoArquivo;
use App\Models\Regras\FatorAvaliacaoRegras;
use App\Models\TipoArquivoRegras;
use Exception;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoArquivoController extends Controller
{

    public function grid()
    {
        return TipoArquivoRegras::grid();
    }

    public function store(Request $request): JsonResponse
    {
        //dd('try');

        $p = (object)$request->validate(['nome' => 'required']);
        DB::beginTransaction();
        try {
            $tipo = TipoArquivoRegras::salvar($p);
            DB::commit();
            return response()->json(["mensagem" => "Novo Tipo de Arquivo salvo com sucesso", "id" => $tipo->id]);

        } catch(Exception $ex) {
            DB::rollBack();
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
