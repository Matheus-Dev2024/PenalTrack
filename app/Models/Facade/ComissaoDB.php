<?php

namespace App\Models\Facade;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class ComissaoDB
{
    public static function carregarParecer($processo_id) :Collection
    {
        $sql = DB::table('processo_avaliacao_servidor as pas')
        ->join("srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
        ->join('periodos_processo as pp', 'pp.id', '=', 'pas.fk_periodo')
        ->join("srh.sig_cargo as sgc", 'sgc.id', '=', 'ss.fk_id_cargo')
        ->leftJoin("parecer_comissao as pc", 'pas.id', '=', 'pc.fk_processo_avaliacao')
        ->select([
            'ss.nome',
            'ss.matricula',
            'pp.nome as periodo',
            'sgc.abreviacao as cargo',
            'pc.parecer',
            'ss.id_servidor',
            DB::raw("TO_CHAR(ss.dt_admissao, 'DD/MM/YYYY') AS admissao"),
        ])
        ->where('pas.id', '=', $processo_id)
        ->get();
        
        return $sql;
    }

    public static function carregarParecerServidor($fk_servidor)
    {
        $sql = DB::table('processo_avaliacao_servidor as pas')
        ->join("srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
        ->join('periodos_processo as pp', 'pp.id', '=', 'pas.fk_periodo')
        ->join("srh.sig_cargo as sgc", 'sgc.id', '=', 'ss.fk_id_cargo')
        ->leftJoin("parecer_comissao as pc", 'pas.id', '=', 'pc.fk_processo_avaliacao')
        ->select([
            // 'pp.nome as periodo',
            // 'sgc.abreviacao as cargo',
            'pc.parecer',
            'ss.id_servidor',
            //DB::raw("TO_CHAR(ss.dt_admissao, 'DD/MM/YYYY') AS admissao"),
        ])
        ->distinct()
        ->where('pas.fk_servidor', '=', $fk_servidor)
        ->where('pas.fk_periodo', '=', 6)  // 6 é o ultimo período
        ->get();
        
        return $sql;
    }

    public static function grid()
    {
        $srh = config('database.connections.conexao_srh.schema');
        $lista = DB::table('comissao')
        ->leftJoin("$srh.sig_servidor as ss", 'ss.id_servidor', '=', 'comissao.presidente')
        //->leftJoin("servidor_comissao as sc", 'sc.fk_comissao', '=', 'comissao.id')
        ->leftJoin("servidor_comissao as sc", function($join) {
            $join->on('sc.fk_comissao', '=', 'comissao.id')
                ->whereNull('sc.deleted_at');
        })
            ->select([
                'comissao.id as comissao_id',
                'numero_comissao',
                'ss.nome as presidente',
                DB::raw('COUNT(CASE WHEN sc.deleted_at IS NULL THEN sc.fk_servidor END) as total_servidores'),
                "comissao.id as comissao_id",

            ])
            ->groupBy('comissao.id', 'comissao.numero_comissao', 'ss.nome')
            ->orderBy('numero_comissao')
            ->get();
        return $lista;
    }

    public static function gridVisualizarComissao($comissao_id)
    {
        //dd($comissao_id);
        $srh = config('database.connections.conexao_srh.schema');
        $lista = DB::table('servidor_comissao as sc')
        ->join("comissao", "comissao.id", "=", "sc.fk_comissao")
        ->leftJoin("$srh.sig_servidor as ss", 'ss.id_servidor', '=', 'sc.fk_servidor')
        ->join("$srh.sig_cargo as sgc", 'sgc.id', '=', 'ss.fk_id_cargo')
        ->leftJoin("$srh.sig_servidor as presidente", 'presidente.id_servidor', '=', 'comissao.presidente')
            ->select([
                'comissao.id',
                'sc.fk_servidor',
                'ss.nome as nome_servidor',
                'ss.matricula',
                'ss.cargo',
                'sgc.abreviacao as sigla_cargo',
                'presidente.nome as nome_presidente'
            ])
            ->whereNull("sc.deleted_at")
            ->orderBy('nome_servidor')
            ->where('sc.fk_comissao', '=', $comissao_id)
            ->get();
        return $lista;
    }

    public static function vincularServidoresGrid(stdClass $p): JsonResponse
    {
        //$srh = config('database.connections.conexao_srh.schema');
        //$policia = config('database.connections.conexao_banco_unico.schema');

        $sql = DB::table('processo_avaliacao_servidor as pas')
            ->join('periodos_processo as pp', 'pp.id', '=', 'pas.fk_periodo')

            // Descomente as linhas abaixo e comente as quatro proximas para funcionar em desenvolvimento
//            ->join("$srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
//            ->LeftJoin("$policia.seguranca.usuario as su", 'su.id', '=', 'pas.fk_avaliador')
//            ->join("$policia.policia.unidade as u", 'u.id', '=', 'pas.fk_unidade')
//            ->join("$srh.sig_cargo as sc", 'sc.id', '=', 'ss.fk_id_cargo')

            ->join("srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
            ->LeftJoin("seguranca.usuario as su", 'su.id', '=', 'pas.fk_avaliador')
            ->LeftJoin("policia.unidade as u", 'u.id', '=', 'pas.fk_unidade')
            ->join("srh.sig_cargo as sc", 'sc.id', '=', 'ss.fk_id_cargo')

            ->LeftJoin("servidor_comissao", 'servidor_comissao.fk_servidor', '=', 'ss.id_servidor')
            ->select(
                'pas.id as id_processo_avaliacao',
                'pas.fk_servidor',
                'sc.abreviacao as sigla_cargo',
                'ss.nome',
                'ss.cargo',
                'ss.matricula',
                'servidor_comissao.fk_comissao'
            )
            ->whereNull('servidor_comissao.deleted_at')
            ->groupBy([
                'ss.nome',
                'pas.id',
                'pas.fk_servidor',
                'sc.abreviacao',
                'ss.cargo',
                'ss.matricula',
                'servidor_comissao.fk_comissao'
            ])
            ->orderBy('ss.nome');

        if (isset($p->periodo_avaliacao)) {
            $sql->where('pas.fk_periodo', $p->periodo_avaliacao);
        }

        $v = $sql->paginate(50);
        //if (!count($v->toArray()) > 0)
        if ($v->isEmpty() || (isset($v->data) && count($v->data) > -1))
            return response()->json(['mensagem' => 'Erro ao carregar os parametros da pesquisa.']);
        return response()->json($v);
    }

}
