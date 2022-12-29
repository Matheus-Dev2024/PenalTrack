<?php

namespace App\Models\Facade;

use Illuminate\Support\Facades\DB;

class ServidorDB
{
    public static function lotacoesPorPeriodo($servidor_id, $dtInicio, $dtTermino=null)
    {
        return DB::select("SELECT * FROM srh.sp_info_servidor_estagio_probatorio('$dtInicio'::date, '$dtTermino'::date, $servidor_id)");
    }

    public static function listaAusenciasPorPeriodo($servidor_id, $dtInicio, $dtTermino=null)
    {
        $ausencias = DB::select("SELECT * FROM srh.sp_ausencias_estagio_probatorio('$dtInicio'::date, '$dtTermino'::date, $servidor_id)");
        return $ausencias;
    }


    public static function info($servidor_id, $processo_id, $dtInicio, $dtTermino)
    {
        //pega os dados do servidor

        $servidor = DB::table('srh.sig_servidor as s')
            ->join('srh.sig_cargo as c', 'c.id', '=', 's.fk_id_cargo')
            ->join('processo_avaliacao_servidor as pas', 'pas.fk_servidor', '=', 's.id_servidor')
            ->join('policia.unidade as u', 'u.id', '=', 'pas.fk_unidade')
            ->select([
                's.id_servidor',
                's.nome',
                's.matricula',
                'c.abreviacao as cargo',
                'u.id as unidade_id',
                'u.nome as unidade',
                'pas.fk_processo_avaliacao',
                'pas.status',
                //'pas.dias_estagio',
                //'pas.dias_trabalho_programado',
                //'pas.dias_ausencia',
                //'pas.dias_trabalhados',
                //'pas.dias_prorrogados',
            ])
            ->where('pas.fk_processo_avaliacao', $processo_id)
            ->where('s.id_servidor', $servidor_id)
            ->first();



        $infoEstagio = self::lotacoesPorPeriodo($servidor_id, $dtInicio, $dtTermino);

        if(count($infoEstagio) > 0)
            $servidor->periodo = $infoEstagio[0];


            
        return $servidor;        
    }








    public static function lotacaoComMaiorTempoTrabalhado($lotacoes)
    {
        if(count($lotacoes) == 0) {
            return null;
        }

        if(count($lotacoes) == 1) {
            return $lotacoes[0];
        }
        
        /*
        * caso mude a procedure sp_lotacoes_por_periodo() e retorne mais de um registro
        * descomentar esta instrução
        *
        $maior = 0; //maior recebe o valor do primeiro elemento do array
        $indiceMaior = null;

        foreach($lotacoes as $i => $item) {
            if($item->dias > $maior) {
                $maior = $item->dias_trabalhados;
                $indiceMaior = $i;
            }
        }


        return $lotacoes[$indiceMaior];
        */
        
    }
}
