<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvaliadorStoreRequest;
use App\Models\Entity\Avaliador;
use App\Models\Facade\AvaliadorDB;
use App\Models\Regras\AvaliadorRegras;
use App\Models\Regras\UsuarioAvaliaServidorRegras;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AvaliadorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $p = (object)$request->validate([
            'id_usuario' => 'required'
        ]);
        $listaAvaliador = AvaliadorDB::PesquisaAvaliador($p);

        return response()->json($listaAvaliador);
    }

    public function show(Avaliador $avaliador)
    {
        //os usuários que possuem foto o sistema retorna um erro (encoding model Type is not supported in file), por isso foi setado null nesta variável
        $avaliador->foto_deprecated = null;
        return response()->json($avaliador);
    }

    public function store(AvaliadorStoreRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $resposta = AvaliadorRegras::salvar((object)$request->all());

            DB::commit();
            return $resposta;

        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(["error" => $ex->getMessage()]);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            AvaliadorRegras::alterar($request);
            DB::commit();
            return response()->json(["mensagem" => "Usuário alterado com sucesso"]);

        } catch (Exception $ex) {
            DB::rollBack();
            //return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
            return response()->json(["error" => $ex->getMessage()]);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            AvaliadorRegras::removerAvaliador($id);

            return response()->json(["mensagem" => "Usuário excluído com sucesso"], 200);
        } catch (Exception $ex) {
            //return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
            return response()->json(["error" => $ex->getMessage()]);
        }
    }

    public function UnidadesGrid(Request $request): JsonResponse
    {

        $lista = AvaliadorDB::unidadesGrid($request->id);
        return response()->json($lista);
    }

    public function AdicionarUnidades(Request $request): JsonResponse
    {
        try {
            $response = AvaliadorRegras::adicionarUnidades($request);
            return response()->json([
                "mensagem" => "Unidade salva com sucesso",
                "resposta" => $response
            ]);
        } catch (Exception $ex) {

            //return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
            return response()->json(["error" => $ex->getMessage()]);
        }
    }

    public function destroyUnidades($request): JsonResponse
    {
        $dados = AvaliadorRegras::removerUnidades($request);
        return response()->json($dados);
    }

    public function removerServidorIndividualmente(Request $request): JsonResponse
    {
        try {
            UsuarioAvaliaServidorRegras::removerServidorAvaliadoIndividualmente($request->id);
            return response()->json(["message" => "excluído com sucesso"]);
        } catch (Exception $ex) {
            return response()->json(["error" => "erro ao excluir o servidor"], 500);
        }
    }

    public function comboAvaliador()
    {
        $comboAvaliador = AvaliadorDB::comboAvaliadorServidor();
        return $comboAvaliador;
    }

}
