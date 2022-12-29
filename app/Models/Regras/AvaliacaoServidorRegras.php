<?php

namespace App\Models\Regras;

use App\Models\Entity\AvaliacaoServidor;
use App\Models\Facade\AvaliacaoDB;
use App\Models\Facade\FatorAvaliacaoDB;
use App\Models\Facade\ProcessoAvaliacaoDB;
use App\Models\Facade\ServidorDB;

class AvaliacaoServidorRegras
{
    public static function info (\stdClass $p){
        $formulario = FatorAvaliacaoDB::getFormularioAvaliacao();
        $processo = ProcessoAvaliacaoDB::getById($p->processo_id);
        $servidor = ServidorDB::info($p->servidor_id, $processo->processo_id, $processo->dt_inicio_avaliacao_en, $processo->dt_termino_avaliacao_en);
        $ausencias = ServidorDB::listaAusenciasPorPeriodo($p->servidor_id, $processo->dt_inicio_avaliacao_en, $processo->dt_termino_avaliacao_en);


        $notas = AvaliacaoDB::getNotasServidor($processo->processo_id, $p->servidor_id);
        $impressao = false;
        if(isset ($notas[0])) $impressao = true;

        return response()->json(['processo' => $processo, 'formulario' => $formulario, 'notas' => $notas, 'servidor' => $servidor, 'ausencias' => $ausencias, 'habilitarimpressao' =>$impressao]);
    }

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
