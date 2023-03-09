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

    public function edit($id): JsonResponse
    {

        $tipo_arquivo = TipoArquivo::find($id);

        if($tipo_arquivo != null) {
            return response()->json([
                "nome" => $tipo_arquivo->nome,
            ]);
        } else {
            return response()->json([
                "mensagem" => "Dado solicitado não encontrado.",
            ]);
        }
    }

    public function update(Request $request): JsonResponse
    {
        $p = (object)$request->validate([
            'id' => 'required',
            'nome' => 'required',
        ]);
        DB::beginTransaction();
        try {
            TipoArquivoRegras::alterar($p);
            DB::commit();
            return response()->json(["mensagem" => "Tipo de Arquivo alterado com sucesso!"]);

        } catch(Exception $ex) {
            DB::rollBack();
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }

    }

    public function delete(TipoArquivo $tipo)
    {
        db::beginTransaction();
        try {
            $tipo->delete();
            DB::commit();
            return response()->json(["mensagem" => "Tipo de Arquivo Excluído com sucesso"]);

        } catch(Exception $ex) {
            DB::rollBack();
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }

    }

}
