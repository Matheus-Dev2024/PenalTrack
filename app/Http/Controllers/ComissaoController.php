<?php

namespace App\Http\Controllers;

use App\Models\Entity\Comissao;
use App\Models\Facade\ComissaoDB;
use App\Models\Regras\ComissaoRegras;
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

    public function alterar(Request $request)
    {
        try {
            ComissaoRegras::alterar($request);
            return response()->json(["mensagem" => "Presidente da comissão alterado com sucesso"]);

        } catch (Exception $ex) {
            return response()->json(["error" => $ex->getMessage()]);
        }

    }
}
