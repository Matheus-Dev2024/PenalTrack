<?php

namespace App\Models\Facade;

use FontLib\TrueType\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

class ProcessoAvaliacaoServidorDB
{
    public static function dadosServidorRelatorio($fk_servidor)
    {
        $srh = config('database.connections.conexao_srh.schema');
        $sql = DB::table("$srh.sig_servidor as ss")
            ->select(
                'ss.nome',
                DB::raw("TO_CHAR(ss.dt_posse, 'DD/MM/YYYY') AS dt_posse"),
                DB::raw("TO_CHAR(ss.dt_nomeacao, 'DD/MM/YYYY') AS dt_nomeacao"),
                DB::raw("TO_CHAR(ss.dt_admissao, 'DD/MM/YYYY') AS dt_admissao"),
                DB::raw("TO_CHAR((ss.dt_admissao + INTERVAL '3 years'), 'DD/MM/YYYY') AS dt_final"),

                'ss.cargo', 'ss.matricula')
            ->where('ss.id_servidor', $fk_servidor);

        return $sql->get();
    }

    public static function dadosAvaliacaoRelatorio($fk_servidor)
    {
        $srh = config('database.connections.conexao_srh.schema');
        $policia = config('database.connections.conexao_banco_unico.schema');

        $sql = DB::table('processo_avaliacao_servidor as pas')
            ->join('processo_avaliacao as pa', 'pa.id', '=', 'pas.fk_processo_avaliacao')
            ->join('periodos_processo as pp', 'pp.id', '=', 'pa.fk_periodo_processo')
            ->join("srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
            ->LeftJoin("seguranca.usuario as su", 'su.id', '=', 'pas.fk_avaliador')
            ->select(
                'su.nome as nome_avaliador',
                'pas.status',
                'pas.nota_total',
                'pa.descricao',
                DB::raw("TO_CHAR(pa.dt_inicio_avaliacao, 'DD/MM/YYYY') AS dt_inicio_avaliacao"),
                DB::raw("TO_CHAR(pa.dt_termino_avaliacao, 'DD/MM/YYYY') AS dt_termino_avaliacao"),
                'pp.nome as periodo'
            )
            ->orderBy('pp.nome')
            ->where('pas.fk_servidor', $fk_servidor)
            ->whereNull('pa.deleted_at');

        return $sql->get();
    }

    public static function dadosItensAvaliacaoRelatorio($fk_servidor)
    {
        $srh = config('database.connections.conexao_srh.schema');

        $sql = DB::table('avaliacao_servidor as a')
            ->join('fator_avaliacao_item as fai', 'fai.id', '=', 'a.fk_fator_avaliacao_item')
            ->join('fator_avaliacao as fa', 'fa.id', '=', 'fai.fk_fator_avaliacao')
            ->join('processo_avaliacao as pa', 'pa.id', '=', 'a.fk_processo_avaliacao')
            ->join('periodos_processo as pp', 'pp.id', '=', 'pa.fk_periodo_processo')
            ->join("srh.sig_servidor as ss", 'ss.id_servidor', '=', 'a.fk_servidor')
            ->select(
                'fa.nome',
                DB::raw('SUM(a.nota) as nota_total'),

                'pp.nome as periodo'
            )
            ->groupBy('fa.nome','pp.nome')
            ->orderBy('fa.nome')
            ->where('a.fk_servidor', $fk_servidor)
            ->whereNull('pa.deleted_at');

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
