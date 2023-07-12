<?php

namespace App\Http\Controllers;

use App\Models\Entity\UsuarioAvaliaUnidades;
use App\Models\Regras\AvaliadorRegras;
use App\Models\Facade\AvaliadorDB;
use Exception;
use Illuminate\Http\Request;


class UnidadesController extends Controller
{
    public function index(Request $request)
    {
        $listaUnidades = AvaliadorDB::gerenciaBusca($request);

        return response()->json($listaUnidades);
    }

    public function store(Request $request)
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

    public function update($id)
    {
        // $dados =  AvaliadorRegras::editar($id);
        // return response()->json($dados);
    }

    public function destroy($request)
    {
        $dados =  AvaliadorRegras::removerUnidades($request);
        return response()->json($dados);
    }
}
