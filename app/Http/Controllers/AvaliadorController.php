<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvaliadorStoreRequest;
use App\Models\Entity\Avaliador;
use App\Models\Entity\UsuarioAvaliaUnidades;
use App\Models\Entity\UsuarioSistema;
use App\Models\Regras\AvaliadorRegras;
use App\Models\Facade\AvaliadorDB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LDAP\Result;

class AvaliadorController extends Controller
{
    public function index()
    {
        $listaAvaliador = AvaliadorDB::PesquisaAvaliador();

        return response()->json($listaAvaliador);
    }

    public function show(Avaliador $avaliador)
    {
        return response()->json($avaliador);
    }

    public function store(AvaliadorStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $avaliador = AvaliadorRegras::salvar($request);

            DB::commit();
            return response()->json(["id" => $avaliador->id, "mensagem" => "Avaliador cadastrado com sucesso"]);
            
        } catch (Exception $ex) {
            DB::rollBack();

            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            AvaliadorRegras::alterar($request);
            DB::commit();
            return response()->json(["mensagem" => "Usuário alterado com sucesso"]);

        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }
    }

    public function destroy($id)
    {
        try {
            AvaliadorRegras::removerAvaliador($id);
            
            return response()->json(["mensagem" => "Usuário excluído com sucesso"], 200);
        } catch (Exception $ex) {
            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }
    }

    public function UnidadesGrid(Request $request)
    {

        $lista = AvaliadorDB::unidadesGrid($request->id);
        return response()->json($lista);
    }

    public function AdicionarUnidades(Request $request)
    {

        try {
            $response  = AvaliadorRegras::adicionarUnidades($request);
            return response()->json([
                "mensagem" => "Unidade salva com sucesso",
                "resposta" => $response
            ]);
        } catch (Exception $ex) {

            return response()->json(["error" => "Opa, ocorreu um erro inesperado. Tente novamente mais tarde."]);
        }
    }

    public function destroyUnidades($request)
    {

        $dados =  AvaliadorRegras::removerUnidades($request);
        return response()->json($dados);
    }
}
