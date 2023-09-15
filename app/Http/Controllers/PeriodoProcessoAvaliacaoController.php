<?php

namespace App\Http\Controllers;

use App\Models\Facade\PeriodoProcessoAvaliacaoDB;

class PeriodoProcessoAvaliacaoController extends Controller
{
    public function combo()
    {
        $lista = PeriodoProcessoAvaliacaoDB::comboPeriodoProcesso();
        return response()->json($lista);
    }
}
