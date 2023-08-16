<?php

namespace App\Models\Facade;

use Illuminate\Support\Facades\DB;

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
                    DB::raw("TO_CHAR(ss.dt_admissao, 'DD/MM/YYYY') AS dt_admissao"),
                    'u.nome as unidade_atual'
                )
                ->whereBetween('ss.dt_admissao', [$ref_inicio, $ref_termino])
                ->whereIn('ss.fk_id_cargo', [24,27,34,40])
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
        $policia = config('database.connections.conexao_banco_unico.schema');

        $itensProcessoAvaliacao = DB::table('processo_avaliacao_servidor as pas')
        ->join('processo_avaliacao as pa', 'pa.id', '=', 'pas.fk_processo_avaliacao')
        ->join("$srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
        ->leftJoin("$srh.sig_documentacao_servidor as ds", 'ds.fk_servidor', 'ss.id_servidor')
        ->leftJoin("$srh.sig_tipo_documento as td", 'ds.fk_tipo_documento' , '=', 'td.id')
        ->join("$policia.policia.unidade as u", 'u.id', '=', 'ss.fk_id_unidade_atual')
        ->join("$srh.sig_cargo as sc", 'sc.id', '=', 'ss.fk_id_cargo')
        ->select(
            'pas.id as id_processo_avaliacao',
            'pas.fk_servidor',
            'sc.abreviacao as sigla_cargo',
            'pa.id',
            'ss.nome',
            
            
            
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
           
           
             DB::raw("TO_CHAR(ss.dt_admissao, 'DD/MM/YYYY') AS dt_admissao"),'u.nome as unidade', 'ss.cargo', 'ss.matricula',)
        ->where('pa.id' ,'=', $id_processo)
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
}
