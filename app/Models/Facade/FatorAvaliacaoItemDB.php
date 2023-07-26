<?php

namespace App\Models\Facade;


use App\Models\Entity\FatorAvaliacaoItem;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\DB;

class FatorAvaliacaoItemDB extends FatorAvaliacaoItem
{
    public static function listarFatorAvaliacao()
    {
        $itensFatorAvaliacao = DB::table('fator_avaliacao_item as i')
            ->join('fator_avaliacao as fa', 'fa.id', '=', 'i.fk_fator_avaliacao')
            ->select('i.*', 'fa.nome as fator_avaliacao')
            ->orderBy('fa')
            ->get();
            
        return $itensFatorAvaliacao;
        
    }
}
