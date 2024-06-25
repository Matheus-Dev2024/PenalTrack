<?php

namespace App\Models\Facade;

use FontLib\TrueType\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

class ProcessoAvaliacaoServidorDB
{
    public static function dadosServidorRelatorio($fk_servidor)
    {
        //$srh = config('database.connections.conexao_srh.schema');
        $sql = DB::table("srh.sig_servidor as ss")
            ->join('processo_avaliacao_servidor as pas', 'pas.fk_servidor', '=', 'ss.id_servidor')
            ->join('periodos_processo as pp', 'pp.id', '=', 'pas.fk_periodo')
            ->select(
                'ss.nome',
                DB::raw("TO_CHAR(ss.dt_posse, 'DD/MM/YYYY') AS dt_posse"),
                DB::raw("TO_CHAR(ss.dt_nomeacao, 'DD/MM/YYYY') AS dt_nomeacao"),
                DB::raw("TO_CHAR(ss.dt_admissao, 'DD/MM/YYYY') AS dt_admissao"),
                DB::raw("TO_CHAR((ss.dt_admissao + INTERVAL '3 years'), 'DD/MM/YYYY') AS dt_final_prevista"),
                //verifica se o servidor ja está no 6 (ultimo) período e tem ausencias > 0 para retornar dt_final_ultimo_periodo
                //essa validação serve pra verificar se o servidor tem o período prorrogado, caso não, dt_final_ultimo_periodo = null
                DB::raw("(SELECT TO_CHAR(pas.dt_termino, 'DD/MM/YYYY')
                      FROM processo_avaliacao_servidor as pas
                      WHERE pas.fk_servidor = ss.id_servidor 
                      AND pas.fk_periodo = 6
                      AND pas.dias_ausencia > 0
                      LIMIT 1) AS dt_final_ultimo_periodo"),
                
                
                'ss.cargo', 
                'ss.matricula')
            ->where('ss.id_servidor', $fk_servidor)
            ->distinct();

        return $sql->get();
    }

    public static function dadosAvaliacaoRelatorio($fk_servidor)
    {
        // $srh = config('database.connections.conexao_srh.schema');
        // $policia = config('database.connections.conexao_banco_unico.schema');

        $sql = DB::table('processo_avaliacao_servidor as pas')
            ->join('periodos_processo as pp', 'pp.id', '=', 'pas.fk_periodo')
            ->join("srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
            ->LeftJoin("seguranca.usuario as su", 'su.id', '=', 'pas.fk_avaliador')
            ->select(
                'su.nome as nome_avaliador',
                'pas.status',
                'pas.nota_total',
                DB::raw("TO_CHAR(pas.dt_inicio, 'DD/MM/YYYY') AS dt_inicio_avaliacao"),
                DB::raw("TO_CHAR(pas.dt_termino, 'DD/MM/YYYY') AS dt_termino_avaliacao"),
                'pp.nome as periodo',
                DB::raw("TO_CHAR((ss.dt_admissao + INTERVAL '3 years'), 'DD/MM/YYYY') AS dt_final_prevista"),
            )
            ->orderBy('pp.nome')
            ->where('pas.fk_servidor', $fk_servidor);

        return $sql->get();
    }

    public static function dadosItensAvaliacaoRelatorio($fk_servidor)
    {
        $srh = config('database.connections.conexao_srh.schema');

        $sql = DB::table('avaliacao_servidor as a')
            ->join('fator_avaliacao_item as fai', 'fai.id', '=', 'a.fk_fator_avaliacao_item')
            ->join('fator_avaliacao as fa', 'fa.id', '=', 'fai.fk_fator_avaliacao')
            ->join('processo_avaliacao_servidor as pas', 'pas.id', '=', 'a.fk_processo_avaliacao_servidor')
            ->join('periodos_processo as pp', 'pp.id', '=', 'pas.fk_periodo')
            ->join("srh.sig_servidor as ss", 'ss.id_servidor', '=', 'a.fk_servidor')
            ->select(
                'fa.nome',
                DB::raw('SUM(a.nota) as nota_total'),

                'pp.nome as periodo'
            )
            ->groupBy('fa.nome','pp.nome')
            ->orderBy('fa.nome')
            ->where('a.fk_servidor', $fk_servidor);

            $resultado = $sql->get();

            $agrupados = [];

            foreach ($resultado as $result) {
                $nome = $result->nome;

                if (!isset($agrupados[$nome])) {
                    $agrupados[$nome] = [];
                }

                $agrupados[$nome][] = [
                    'nota_total' => $result->nota_total,
                    'periodo' => $result->periodo,
                ];
            }

            return $agrupados;
    }

    public static function getProcessoAvaliacaoServidor($fk_servidor) :SupportCollection
    {
        $srh = config('database.connections.conexao_srh.schema');
        $policia = config('database.connections.conexao_banco_unico.schema');

        $sql = DB::table('processo_avaliacao_servidor as pas')
            ->leftJoin("$srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
            ->leftJoin("$policia.seguranca.usuario as su", 'su.id', '=', 'pas.fk_avaliador')
            ->select(
                'su.nome as nome_avaliador',
                'pas.status',
                'pas.nota_total',
                'pas.dt_inicio',
                'pas.dt_termino'
            )
            ->orderBy('pas.created_at', 'DESC')
            ->where('pas.fk_servidor', $fk_servidor);

        return $sql->get();
    }

    public static function getById($processo_id)
    {
        return DB::table('processo_avaliacao_servidor as pas')
            ->join('periodos_processo as pp', 'pp.id', '=', 'pas.fk_periodo')
            ->leftJoin("seguranca.usuario as u", 'u.id', '=', 'pas.fk_avaliador')
            ->select([
                'pas.id as processo_id',
                'pp.nome as periodo',
                'pas.dt_inicio',
                'pas.dt_termino',
                'pas.parecer_avaliador',
                'u.nome as nome_avaliador',
                'pas.fk_avaliador',
                //DB::raw("TO_CHAR(pas.dt_inicio, 'DD/MM/YYYY') AS dt_inicio_estagio"),
                DB::raw("TO_CHAR(pas.dt_inicio, 'DD/MM/YYYY') AS dt_inicio_avaliacao"),
                DB::raw("TO_CHAR(pas.dt_termino, 'DD/MM/YYYY') AS dt_termino_avaliacao"),

            ])
            ->where('pas.id', $processo_id)
            ->first();
    }

}
