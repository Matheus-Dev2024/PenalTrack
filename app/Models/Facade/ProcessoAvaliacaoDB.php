<?php

namespace App\Models\Facade;

use Illuminate\Support\Facades\DB;

class ProcessoAvaliacaoDB
{
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
                                ->get();

            //dd($itensProcessoAvaliacao);
            
        return $itensProcessoAvaliacao;
    }

    public static function listarServidoresGrid($ref_inicio, $ref_termino)
    {
        $listaProcessoAvaliacao = DB::table('sig_servidor as ss')
        ->join('policia.unidade as u', 'u.id', '=', 'ss.fk_id_unidade_atual')
        ->join('sig_cargo as sc', 'sc.id', '=', 'ss.fk_id_cargo')
        ->whereBetween('ss.dt_admissao', [$ref_inicio, $ref_termino])
        ->select('ss.id_servidor as servidor', 'ss.nome','ss.matricula','sc.abreviacao as sigla_cargo', 'sc.nome as cargo',
        DB::raw("TO_CHAR(ss.dt_admissao, 'DD/MM/YYYY') AS dt_admissao"),
         'u.nome as unidade')
        ->get();
        return $listaProcessoAvaliacao;
    }

    public static function servidoresGrid($id_processo)
    {
        $itensProcessoAvaliacao = DB::table('processo_avaliacao_servidor as pas')
        ->join('processo_avaliacao as pa', 'pa.id', '=', 'pas.fk_processo_avaliacao')
        ->join('sig_servidor as ss', 'ss.id_servidor', '=', 'pas.fk_servidor')
        ->join('policia.unidade as u', 'u.id', '=', 'ss.fk_id_unidade_atual')
        ->join('sig_cargo as sc', 'sc.id', '=', 'ss.fk_id_cargo')
        ->select('pas.id as id_processo_avaliacao','pas.fk_servidor','sc.abreviacao as sigla_cargo', 'pa.id', 'ss.nome', DB::raw("TO_CHAR(ss.dt_admissao, 'DD/MM/YYYY') AS dt_admissao"),'u.nome as unidade', 'ss.cargo', 'ss.matricula')
        ->where('pa.id' ,'=', $id_processo)
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

}
