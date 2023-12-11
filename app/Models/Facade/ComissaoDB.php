<?php

namespace App\Models\Facade;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class ComissaoDB
{
    public static function grid()
    {
        $srh = config('database.connections.conexao_srh.schema');
        $lista = DB::table('comissao')
        ->leftJoin("$srh.sig_servidor as ss", 'ss.id_servidor', '=', 'comissao.presidente')
            ->select([
                'id',
                'numero_comissao',
                'ss.nome as presidente'
            ])
            ->orderBy('numero_comissao')
            ->get();
        return $lista;
    }

    public static function vincularServidoresGrid(stdClass $p): JsonResponse
    {
        $srh = config('database.connections.conexao_srh.schema');
        $policia = config('database.connections.conexao_banco_unico.schema');

        $sql = DB::table('processo_avaliacao_servidor as pas')
            ->join('processo_avaliacao as pa', 'pa.id', '=', 'pas.fk_processo_avaliacao')
            ->join("$srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
            ->LeftJoin("servidor_comissao", 'servidor_comissao.fk_servidor', '=', 'ss.id_servidor')
            ->LeftJoin("$policia.seguranca.usuario as su", 'su.id', '=', 'pas.fk_avaliador')
            ->join("$policia.policia.unidade as u", 'u.id', '=', 'pas.fk_unidade')
            ->join("$srh.sig_cargo as sc", 'sc.id', '=', 'ss.fk_id_cargo')
            ->select(
                'pas.id as id_processo_avaliacao',
                'pas.fk_servidor',
                'sc.abreviacao as sigla_cargo',
                'pa.id',
                'ss.nome',
                'ss.cargo',
                'ss.matricula',
                'servidor_comissao.fk_comissao'
            )
            ->whereNull('pa.deleted_at')
            ->whereNull('servidor_comissao.deleted_at')
            ->groupBy([
                'ss.nome',
                'pas.id',
                'pas.fk_servidor',
                'sc.abreviacao',
                'pa.id',
                'ss.cargo',
                'ss.matricula',
                'servidor_comissao.fk_comissao'
            ])
            ->orderBy('ss.nome');

        if (isset($p->processo_avaliacao)) {
            $sql->where('pa.id', $p->processo_avaliacao);
        }


        $v = $sql->paginate(50);
        //if (!count($v->toArray()) > 0)
        if ($v->isEmpty() || (isset($v->data) && count($v->data) > 0))
            return response()->json(['mensagem' => 'Erro ao carregar os parametros da pesquisa.'], 412);
        return response()->json($v);
    }

}