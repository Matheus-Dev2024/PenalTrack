<?php

namespace App\Models\Facade;

use Illuminate\Support\Facades\DB;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProcessoAvaliacaoDB
{
    public static function getById($processo_id)
    {
        return DB::table('processo_avaliacao as pa')
            ->select([
                'pa.id as processo_id',
                'pa.descricao',
                'pa.dt_inicio_estagio as dt_inicio_estagio_en',
                DB::raw("TO_CHAR(pa.dt_inicio_estagio, 'DD/MM/YYYY') AS dt_inicio_estagio"),
                'pa.instrucao',
                'pa.dt_inicio_avaliacao as dt_inicio_avaliacao_en',
                'pa.dt_termino_avaliacao as dt_termino_avaliacao_en',
                DB::raw("TO_CHAR(pa.dt_inicio_avaliacao, 'DD/MM/YYYY') AS dt_inicio_avaliacao"),
                DB::raw("TO_CHAR(pa.dt_termino_avaliacao, 'DD/MM/YYYY') AS dt_termino_avaliacao"),
                DB::raw("
                        (SELECT COUNT(*) FROM processo_avaliacao_servidor pas WHERE pas.fk_processo_avaliacao = pa.id) AS qtd_servidores
                    ")
            ])
            ->where('pa.id', $processo_id)
            ->first();
    }


    public static function listaProcessoAvaliacao()
    {

        $itensProcessoAvaliacao = DB::table('processo_avaliacao as pa')
            ->select([
                'pa.id as id_periodo',
                'pa.descricao',
                DB::raw("TO_CHAR(pa.dt_inicio_avaliacao, 'DD/MM/YYYY') AS dt_inicio_avaliacao"),
                DB::raw("TO_CHAR(pa.dt_termino_avaliacao, 'DD/MM/YYYY') AS dt_termino_avaliacao"),
                DB::raw("
                                        (SELECT COUNT(*) FROM processo_avaliacao_servidor pas WHERE pas.fk_processo_avaliacao = pa.id) AS qtd_servidores
                                    ")
            ])
            ->whereNull('deleted_at')
            ->get();


        return $itensProcessoAvaliacao;
    }


    /**
     * SRH
     */
    //public static function listarServidoresGrid($ref_inicio, $ref_termino, $dt_inicio_avaliacao, $dt_fim_avaliacao)


    public static function listarServidoresGrid($ref_inicio, $ref_termino)
    {
        $srh = config('database.connections.conexao_srh.schema');
        $policia = config('database.connections.conexao_banco_unico.schema');

        $listaProcessoAvaliacao =
            DB::table("$srh.sig_servidor as ss")
                ->join("$policia.unidade as u", 'u.id', '=', 'ss.fk_id_unidade_atual')
                ->join("$srh.sig_cargo as sc", 'sc.id', '=', 'ss.fk_id_cargo')
                ->select(
                    'ss.id_servidor as servidor',
                    'ss.nome',
                    'ss.matricula',
                    'sc.abreviacao as sigla_cargo',
                    'sc.nome as cargo',
                    'ss.dt_admissao as data_exercicio',
                    DB::raw("TO_CHAR(ss.dt_admissao, 'DD/MM/YYYY') AS dt_admissao"),
                    'u.nome as unidade_atual'
                )
                ->whereBetween('ss.dt_admissao', [$ref_inicio, $ref_termino])
                ->whereIn('ss.fk_id_cargo', [24, 27, 34, 40])
                ->where('ss.status', 1)
                ->orderBy('ss.nome')
                ->get();

        /*
        if($listaProcessoAvaliacao) {
            foreach($listaProcessoAvaliacao as $i => $item) {
                $info = DB::select("SELECT * FROM srh.sp_info_servidor_estagio_probatorio('$dt_inicio_avaliacao', '$dt_fim_avaliacao', $item->servidor)");

                if($info) {
                    $listaProcessoAvaliacao[$i]->id_unidade_avaliacao = $info[0]->fk_id_unidade;
                    $listaProcessoAvaliacao[$i]->unidade_avaliacao = $info[0]->lotacao;
                }
            }
        }
        */

        return $listaProcessoAvaliacao;
    }



    // public static function listarServidoresGrid($ref_inicio, $ref_termino)
    // {
    //     $srh = config('database.connections.conexao_srh.schema');
    //     $policia = config('database.connections.conexao_banco_unico.schema');

    //     $listaProcessoAvaliacao =
    //         DB::table("$srh.sig_servidor as ss")
    //             ->join("$policia.unidade as u", 'u.id', '=', 'ss.fk_id_unidade_atual')
    //             ->join("$srh.sig_cargo as sc", 'sc.id', '=', 'ss.fk_id_cargo')
    //             ->select(
    //                 'ss.id_servidor as servidor',
    //                 'ss.nome',
    //                 'ss.matricula',
    //                 'sc.abreviacao as sigla_cargo',
    //                 'sc.nome as cargo',
    //                 DB::raw("TO_CHAR(ss.dt_admissao, 'DD/MM/YYYY') AS dt_admissao"),
    //                 'u.nome as unidade_atual'
    //             )
    //             ->whereBetween('ss.dt_admissao', [$ref_inicio, $ref_termino])
    //             ->whereIn('ss.fk_id_cargo', [24,27,34,40])
    //             ->where('ss.status', 1)
    //             ->orderBy('ss.nome')
    //             ->get();

    /*
    if($listaProcessoAvaliacao) {
        foreach($listaProcessoAvaliacao as $i => $item) {
            $info = DB::select("SELECT * FROM srh.sp_info_servidor_estagio_probatorio('$dt_inicio_avaliacao', '$dt_fim_avaliacao', $item->servidor)");

            if($info) {
                $listaProcessoAvaliacao[$i]->id_unidade_avaliacao = $info[0]->fk_id_unidade;
                $listaProcessoAvaliacao[$i]->unidade_avaliacao = $info[0]->lotacao;
            }
        }
    }
    */


    //     return $listaProcessoAvaliacao;
    // }

    public static function servidoresGrid($id_processo)
    {
        $srh = config('database.connections.conexao_srh.schema');
        $policia = config('database.connections.conexao_banco_unico.database');

        $itensProcessoAvaliacao = DB::table('processo_avaliacao_servidor as pas')
            ->join('processo_avaliacao as pa', 'pa.id', '=', 'pas.fk_processo_avaliacao')
            ->join("$srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
            ->LeftJoin("$policia.seguranca.usuario as su", 'su.id', '=', 'pas.fk_avaliador')
            //a função de callback serve para para executar o where() somente dentro do LeftJoin || a exibicao_documento = 2 é para exibir no estágio
            ->leftJoin("$srh.sig_documentacao_servidor as ds", function ($join) {
                $join->on('ds.fk_servidor', '=', 'ss.id_servidor')
                ->where('ds.exibicao_documento', '=', 2);
            })
            //->where('ds.exibicao_documento', '=', 2)
            ->leftJoin("$srh.sig_tipo_documento as td", 'ds.fk_tipo_documento', '=', 'td.id')
            ->join("$policia.policia.unidade as u", 'u.id', '=', 'ss.fk_id_unidade_atual')
            ->join("$srh.sig_cargo as sc", 'sc.id', '=', 'ss.fk_id_cargo')
            ->select(
                'pas.id as id_processo_avaliacao',
                'pas.fk_servidor',
                'sc.abreviacao as sigla_cargo',
                'pa.id',
                'ss.nome',
                'su.nome as nome_avaliador',
                'pas.status',
                'pas.nota_total',


                DB::raw("STRING_AGG(


                    '
                    <table style=\"width: 100%;\" >
                    <tr>
                        <td >
                            <a href=\"#' || ds.id::text || '\" onclick=\"abrirPdfNovaAba(' || ds.id || ')\">
                                <i class=\"glyphicon glyphicon-paperclip\">&nbsp;</i>' || td.nome || '
                            </a>
                        </td>
                        <td style=\"text-align: right;\">
                            <btn onclick=\"deletar(' || ds.id || ')\">
                                <a href=\"#\" class=\"glyphicon glyphicon-trash\"></a>
                            </btn>

                        </td>
                    </tr>
                    </table>

                    '

                    ,
                  '')
                    as documentos"
                ),

                //  DB::raw("STRING_AGG(

                //     '<a href=\"#' || ds.id::text || '\" onclick=\"abrirPdfNovaAba(' || ds.id || ')\">
                //         <i class=\"glyphicon glyphicon-paperclip\">&nbsp;</i>' || td.nome || '
                //     </a>
                //     <btn onclick=\"deletar(' || ds.id || ')\" class=\"btn\">
                //         <i class=\"glyphicon glyphicon-trash\"></i>
                //     </btn>
                //     <br>',
                //   '')
                //     as documentos"
                // ),


                DB::raw("TO_CHAR(ss.dt_admissao, 'DD/MM/YYYY') AS dt_admissao"), 'u.nome as unidade', 'ss.cargo', 'ss.matricula',)
            ->where('pa.id', '=', $id_processo)
            ->groupBy([
                'ss.nome',
                'pas.id',
                'pas.fk_servidor',
                'sc.abreviacao',
                'pa.id',
                'ss.dt_admissao',
                'u.nome',
                'ss.cargo',
                'ss.matricula',
                'su.nome'

            ])
            ->get();

        return $itensProcessoAvaliacao;
    }


    public static function listarServidor()
    {
        $servidor = DB::table('sig_servidor')
            ->select([
                'id_servidor',
                DB::raw('UPPER(nome) as nome')
            ])
            ->get();

        return $servidor;
    }

    public static function getServidoresProcesso()
    {
        $srh = config('database.connections.conexao_srh.schema');
        return DB::table('processo_avaliacao_servidor as pas')
            ->join("$srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
            ->distinct()
            ->orderBy('ss.nome')
            ->get([
                'pas.fk_servidor as id',
                'ss.nome as name'
            ]);
    }

    public static function pesquisarDescricao()
    {
        $descricao = DB::table('processo_avaliacao')
            ->select([
                'id',
                'descricao'
            ])
            ->get();

        return $descricao;
    }

    public static function getNotaTotalServidor($processo_id, $servidor_id)
    {
        $notas = AvaliacaoDB::getNotasServidor($processo_id, $servidor_id);
        return number_format($notas->sum("nota"), 1);
    }

    public static function acompanhamentoServidoresGrid(stdClass $p): JsonResponse
    {
        // $srh = config('database.connections.conexao_srh.schema');
        // $policia = config('database.connections.conexao_banco_unico.schema');

        $sql = DB::table('processo_avaliacao_servidor as pas')
            ->join("srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
            ->LeftJoin("seguranca.usuario as su", 'su.id', '=', 'pas.fk_avaliador')
            ->leftJoin("srh.sig_documentacao_servidor as ds", function ($join) {
                $join->on('ds.fk_servidor', '=', 'ss.id_servidor')
                    ->where('ds.exibicao_documento', '=', 2);
            })
            ->leftJoin("srh.sig_tipo_documento as td", 'ds.fk_tipo_documento', '=', 'td.id')
            ->join("periodos_processo as pp", 'pp.id', '=', 'pas.fk_periodo' )
            ->LeftJoin("policia.unidade as u", 'u.id', '=', 'pas.fk_unidade')
            ->join("srh.sig_cargo as sc", 'sc.id', '=', 'ss.fk_id_cargo')
            ->select(
                'pas.id as id_processo_avaliacao',
                'pas.fk_servidor',
                'sc.abreviacao as sigla_cargo',
                'ss.nome',
                'su.nome as nome_avaliador',
                'pas.status',
                'pas.nota_total',
                'pas.fk_unidade',
                'pas.fk_avaliador',
                'pp.nome as periodo',
                'pp.id as id_periodo',
                // DB::raw("STRING_AGG(


                //     '
                //     <table style=\"width: 100%;\" >
                //     <tr>
                //         <td >
                //             <a href=\"#' || ds.id::text || '\" onclick=\"abrirDocumentacaoPdfNovaAba(' || ds.id || ')\">
                //                 <i class=\"glyphicon glyphicon-paperclip\">&nbsp;</i>' || td.nome || '
                //             </a>
                //         </td>
                        
                //     </tr>
                //     </table>

                //     '

                //     ,
                //   '')
                //     as documentos"
                // ),

                DB::raw("TO_CHAR(ss.dt_admissao, 'DD/MM/YYYY') AS dt_admissao"), 'u.nome as unidade', 'ss.cargo', 'ss.matricula',)
            ->groupBy([
                'ss.nome',
                'pas.id',
                'pas.fk_servidor',
                'sc.abreviacao',
                'ss.dt_admissao',
                'u.nome',
                'ss.cargo',
                'ss.matricula',
                'su.nome',
                'periodo',
                'pp.id'
            ])
            ->orderBy('ss.nome');

        if (isset($p->periodo_processo)) {
            $sql->where('pp.id', $p->periodo_processo);
        }
        if (isset($p->processo_avaliacao_unidade)) {
            $sql->where('pas.fk_unidade', $p->processo_avaliacao_unidade);
        }
        if (isset($p->avaliador)) {
            $sql->where('pas.fk_avaliador', $p->avaliador);
        }
        if (isset($p->servidor)) {
            $sql->where('pas.fk_servidor', $p->servidor);
        }
        if (isset($p->status)) {
            $sql->where('pas.status', $p->status);
        }
        //filtro de pesquisa por data de período
        if (isset($p->ref_inicio_admissao,$p->ref_fim_admissao)){
            $sql->whereBetween('ss.dt_admissao', [$p->ref_inicio_admissao,$p->ref_fim_admissao]);
        }


        $v = $sql->paginate(50);
        //if (!count($v->toArray()) > 0)
        if ($v->isEmpty() || (isset($v->data) && count($v->data) > 0))
            return response()->json(['mensagem' => 'Verifique se existe um servidor, unidade ou o avaliador existe no processo selecionado.'], 412);
        return response()->json($v);
    }
    // public static function acompanhamentoServidoresGrid(stdClass $p): JsonResponse
    // {
    //     $srh = config('database.connections.conexao_srh.schema');
    //     $policia = config('database.connections.conexao_banco_unico.schema');

    //     $sql = DB::table('processo_avaliacao_servidor as pas')
    //         ->join('processo_avaliacao as pa', 'pa.id', '=', 'pas.fk_processo_avaliacao')
    //         ->join("$srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
    //         ->LeftJoin("$policia.seguranca.usuario as su", 'su.id', '=', 'pas.fk_avaliador')
    //         ->leftJoin("$srh.sig_documentacao_servidor as ds", function ($join) {
    //             $join->on('ds.fk_servidor', '=', 'ss.id_servidor')
    //                 ->where('ds.exibicao_documento', '=', 2);
    //         })
    //         ->leftJoin("$srh.sig_tipo_documento as td", 'ds.fk_tipo_documento', '=', 'td.id')
    //         ->join("$policia.policia.unidade as u", 'u.id', '=', 'pas.fk_unidade')
    //         ->join("$srh.sig_cargo as sc", 'sc.id', '=', 'ss.fk_id_cargo')
    //         ->select(
    //             'pas.id as id_processo_avaliacao',
    //             'pas.fk_servidor',
    //             'sc.abreviacao as sigla_cargo',
    //             'pa.id',
    //             'ss.nome',
    //             'su.nome as nome_avaliador',
    //             'pas.status',
    //             'pas.nota_total',
    //             'pas.fk_unidade',
    //             'pas.fk_avaliador',
    //             'pa.descricao',
    //             DB::raw("STRING_AGG(


    //                 '
    //                 <table style=\"width: 100%;\" >
    //                 <tr>
    //                     <td >
    //                         <a href=\"#' || ds.id::text || '\" onclick=\"abrirDocumentacaoPdfNovaAba(' || ds.id || ')\">
    //                             <i class=\"glyphicon glyphicon-paperclip\">&nbsp;</i>' || td.nome || '
    //                         </a>
    //                     </td>
                        
    //                 </tr>
    //                 </table>

    //                 '

    //                 ,
    //               '')
    //                 as documentos"
    //             ),

    //             DB::raw("TO_CHAR(ss.dt_admissao, 'DD/MM/YYYY') AS dt_admissao"), 'u.nome as unidade', 'ss.cargo', 'ss.matricula',)
    //         ->whereNull('pa.deleted_at')
    //         ->groupBy([
    //             'ss.nome',
    //             'pas.id',
    //             'pas.fk_servidor',
    //             'sc.abreviacao',
    //             'pa.id',
    //             'ss.dt_admissao',
    //             'u.nome',
    //             'ss.cargo',
    //             'ss.matricula',
    //             'su.nome'
    //         ]);

    //     if (isset($p->processo_avaliacao)) {
    //         $sql->where('pa.id', $p->processo_avaliacao);
    //     }
    //     if (isset($p->processo_avaliacao_unidade)) {
    //         $sql->where('pas.fk_unidade', $p->processo_avaliacao_unidade);
    //     }
    //     if (isset($p->avaliador)) {
    //         $sql->where('pas.fk_avaliador', $p->avaliador);
    //     }
    //     if (isset($p->servidor)) {
    //         $sql->where('pas.fk_servidor', $p->servidor);
    //     }
    //     if (isset($p->status)) {
    //         $sql->where('pas.status', $p->status);
    //     }

    //     $v = $sql->paginate(50);
    //     //if (!count($v->toArray()) > 0)
    //     if ($v->isEmpty() || (isset($v->data) && count($v->data) > 0))
    //         return response()->json(['mensagem' => 'Verifique se existe um servidor, unidade ou o avaliador existe no processo selecionado.'], 412);
    //     return response()->json($v);
    // }


    public static function comboUnidade()
    {
        //$policia = config('database.connections.conexao_banco_unico.schema');
        return DB::table('processo_avaliacao_servidor as pas')
            ->join("policia.unidade as u", 'u.id', '=', 'pas.fk_unidade')
            ->orderBy('u.nome')
            ->distinct()
            ->get([
                'pas.fk_unidade as id',
                'u.nome as name'
            ]);
    }

    public static function comboProcessoTelaAcompanhamento()
    {
        return DB::table('processo_avaliacao')
            ->orderBy('descricao')
            ->whereNull('deleted_at')
            ->get([
                'id as id',
                'descricao as name'
            ]);
    }

    public static function comboStatusTelaAcompanhamento()
    {
        return DB::table('processo_situacao_servidor')
            ->get([
                'id as id',
                'nome as name'
            ]);
    }
}
