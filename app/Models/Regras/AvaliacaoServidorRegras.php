<?php

namespace App\Models\Regras;

use App\Models\Entity\AvaliacaoServidor;
use App\Models\Entity\ProcessoAvaliacaoServidor;

class AvaliacaoServidorRegras
{
    public static function adicionarNotas($p)
    {

        AvaliacaoServidor::where('fk_processo_avaliacao', $p->processo_avaliacao_id)
                        ->where('fk_servidor', $p->servidor_id)
                        ->delete();

        foreach($p->notas as $item_id => $nota) {
            
            if(!empty($nota)) {                

                AvaliacaoServidor::create([
                    'fk_processo_avaliacao'   => $p->processo_avaliacao_id,
                    'fk_servidor'             => $p->servidor_id,
                    'fk_fator_avaliacao_item' => $item_id,
                    'nota'                    => $nota
                ]);

            }
        }     
    }
}
