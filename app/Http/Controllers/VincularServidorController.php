<?php

namespace App\Http\Controllers;

use App\Models\Facade\ComissaoDB;
use App\Models\Facade\PeriodoProcessoAvaliacaoDB;
use App\Models\Regras\ServidorComissaoRegras;
use Exception;
use Illuminate\Http\Request;

class VincularServidorController extends Controller
{
    //
    public function grid(Request $request)
    {
        $p = (object)$request->all();
        return ComissaoDB::vincularServidoresGrid($p);
    }

    public function store(Request $request)
    {   
        try {
            ServidorComissaoRegras::salvar($request);
            return response()->json(["mensagem" => "Comissão do servidor alterada com sucesso!"]);

        } catch(Exception $ex) {
            return response()->json(["error" => $ex->getMessage()]);
        }
    }

    public function info()
    {
        $info = [];
        $info['periodo'] = PeriodoProcessoAvaliacaoDB::comboPeriodoProcesso();
        $info['tipo_comissao'] = ComissaoDB::comboCargoComissaoVincularServidor();

        return $info;
    }




}
