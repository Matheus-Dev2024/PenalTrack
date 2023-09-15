<?php

namespace App\Models\Facade;


use App\Models\Entity\PeriodoProcessoAvaliacao;
use Illuminate\Support\Collection;

class PeriodoProcessoAvaliacaoDB
{
    public static function comboPeriodoProcesso(): Collection
    {
        return PeriodoProcessoAvaliacao::all([
            'id as value',
            'nome as text'
        ]);
    }
}
