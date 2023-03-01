<?php

namespace App\Models\Facade;

use App\Models\Entity\AvaliacaoServidor;
use Illuminate\Support\Facades\DB;

class AvaliacaoDB
{
    public static function getFormulario()
    {
        $form = DB::table("fator_avaliacao as fa")
                    ->join("fator_avaliacao_item as fai", "fai.fk_fator_avaliacao", "=", "fa.id")
                    ->select([
                        "fa.id as fator_avaliacao_id",
                        "fa.nome as fator",
                        "fa.descricao as descricao_fator",
                        "fai.id as fator_avaliacao_item_id",
                        "fai.pergunta",
                        "fai.status as status_item"
                    ])
                    ->where("fai.status", 1)
                    ->get();

        return $form;
    }



    public static function getNotasServidor($processo_id, $servidor_id)
    {
        return AvaliacaoServidor::where('fk_processo_avaliacao', $processo_id)->where('fk_servidor', $servidor_id)->get();
    }


    public static function gridArquivos(\stdClass $p){
        //return $p;
        $sql = DB::table('arquivo_avaliacao_servidor as arquivo')
            ->join('tipo_arquivo as tipo', 'tipo.id', 'arquivo.fk_tipo_arquivo')
            ->where('arquivo.fk_processo_avaliacao', $p->processo_avaliacao_id)
            ->where('arquivo.fk_servidor', $p->servidor_id)
            ->select([
                'arquivo.id',
                'nome_arquivo',
                'descricao',
                'tipo.nome as tipo'
            ]);

        return $sql->get();
    }

}
