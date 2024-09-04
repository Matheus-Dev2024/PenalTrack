<?php

namespace App\Http\Controllers;

use App\Models\Facade\PeriodoProcessoAvaliacaoDB;
use Illuminate\Http\Request;

class PeriodoProcessoAvaliacaoController extends Controller
{
    public function combo(Request $request)
    {
        $lista = PeriodoProcessoAvaliacaoDB::comboPeriodoProcesso();
        return response()->json($lista);
    }

    public function comboAutoComplete()
    {
        $lista = PeriodoProcessoAvaliacaoDB::comboPeriodoProcessoAutoComplete();
        return response()->json($lista);
    }
}
