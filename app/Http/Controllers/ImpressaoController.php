<?php

namespace App\Http\Controllers;
use App\Models\Regras\ImpressaoRegras;
use Illuminate\Http\Request;

class ImpressaoController extends Controller
{

    public function imprimir(Request $request)
    {

        $p = (object) $request->all();

        // Impressão de Avaliação
        if(isset($p->imprime_avaliacao)){
            return ImpressaoRegras::imprimirAvaliacao($p);
        }

        return response()->json([
            'message' => 'Não foi possível identificar o timpo de impressão solicitada'
        ]);
    }

}
