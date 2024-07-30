<?php

namespace App\Models\Facade;

use App\Models\Entity\TipoComissao;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class ComissaoDB
{

    public static function comboCargoComissaoVincularServidor() :Collection
    {
        return DB::table('comissao')
        ->join('srh.sig_cargo as cargo', 'comissao.fk_cargo_comissao', '=', 'cargo.id')
        ->orderBy('comissao.id')
        ->get([
            // 'comissao.id as id_comissao',
            'cargo.abreviacao as text',
            'comissao.id as value',
            'comissao.numero_comissao',
            'cargo.id as id_cargo'
        ]);
    }

    public static function comboCargoComissao(): Collection
    {
        $cargos = [
            24, //DPC
            27, //EPC
            40, //PAP
            34 //IPC
        ];
        return DB::table('srh.sig_cargo as cargo')
            ->whereIn('cargo.id', $cargos)
            ->get([
                'abreviacao as text',
                'id as value',
            ]);
    }
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

    public static function carregarComissaoParecer($fk_servidor) :Collection
    {
        $sql = DB::table('comissao')
        ->join("srh.sig_servidor as presidente", 'presidente.id_servidor', '=', 'comissao.presidente')
        ->join("srh.sig_servidor as primeiro_membro", 'primeiro_membro.id_servidor', '=', 'comissao.primeiro_membro')
        ->join("srh.sig_servidor as segundo_membro", 'segundo_membro.id_servidor', '=', 'comissao.segundo_membro')
        ->join("servidor_comissao as sc", function($join) {
            $join->on('sc.fk_comissao', '=', 'comissao.id')
                ->whereNull('sc.deleted_at');
        })
        ->select([
            'presidente.nome as nome_presidente',
            'primeiro_membro.nome as primeiro_membro_nome',
            'segundo_membro.nome as segundo_membro_nome',
        ])
        ->where('sc.fk_servidor', '=', $fk_servidor)
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
        $lista = DB::table('comissao')
        // ->join("tipo_comissao as tc", 'tc.id', '=', 'comissao.fk_tipo_comissao')
        ->leftJoin("srh.sig_servidor as ss", 'ss.id_servidor', '=', 'comissao.presidente')
        ->leftJoin("srh.sig_servidor as primeiro_membro", 'primeiro_membro.id_servidor', '=', 'comissao.primeiro_membro')
        ->leftJoin("srh.sig_servidor as segundo_membro", 'segundo_membro.id_servidor', '=', 'comissao.segundo_membro')
        ->join("srh.sig_cargo as sgc", 'sgc.id', '=', 'ss.fk_id_cargo')
        ->join('srh.sig_cargo as cargo_comissao', 'cargo_comissao.id', '=', 'comissao.fk_cargo_comissao')
        //->leftJoin("servidor_comissao as sc", 'sc.fk_comissao', '=', 'comissao.id')
        ->leftJoin("servidor_comissao as sc", function($join) {
            $join->on('sc.fk_comissao', '=', 'comissao.id')
                ->whereNull('sc.deleted_at');
        })
            ->select([
                'comissao.id as comissao_id',
                'numero_comissao',
                'ss.nome as presidente',
                'ss.id_servidor as id_presidente',
                DB::raw('COUNT(CASE WHEN sc.deleted_at IS NULL THEN sc.fk_servidor END) as total_servidores'),
                "comissao.id as comissao_id",
                'cargo_comissao.abreviacao as cargo_avaliado',
                'comissao.fk_cargo_comissao',
                // 'sgc.abreviacao as cargo',
                'primeiro_membro.nome as primeiro_membro_nome',
                'primeiro_membro.id_servidor as id_primeiro_membro',
                'segundo_membro.nome as segundo_membro_nome',
                'segundo_membro.id_servidor as id_segundo_membro',

            ])
            ->groupBy(
            'comissao.id', 
            'comissao.numero_comissao', 
            'ss.nome', 
            'id_presidente', 
            // 'sgc.abreviacao', 
            'comissao.fk_cargo_comissao',
            'primeiro_membro.nome', 
            'primeiro_membro.id_servidor', 
            'segundo_membro.nome', 
            'segundo_membro.id_servidor',
            'cargo_comissao.abreviacao'
            )
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
        ->leftJoin("$srh.sig_servidor as primeiro_membro", 'primeiro_membro.id_servidor', '=', 'comissao.primeiro_membro')
        ->leftJoin("$srh.sig_servidor as segundo_membro", 'segundo_membro.id_servidor', '=', 'comissao.segundo_membro')

            ->select([
                'comissao.id',
                'sc.fk_servidor',
                'ss.nome as nome_servidor',
                'ss.matricula',
                'ss.cargo',
                'sgc.abreviacao as sigla_cargo',
                'presidente.nome as nome_presidente',
                'primeiro_membro.nome as primeiro_membro_nome',
                'segundo_membro.nome as segundo_membro_nome',
            ])
            ->whereNull("sc.deleted_at")
            ->orderBy('nome_servidor')
            ->where('sc.fk_comissao', '=', $comissao_id)
            ->get();
        return $lista;
    }

    public static function vincularServidoresGrid(stdClass $p): JsonResponse
    {
        $sql = DB::table('srh.sig_servidor as ss')
            ->join("processo_avaliacao_servidor as pas", 'ss.id_servidor', '=', 'pas.fk_servidor')
            ->join('periodos_processo as pp', 'pp.id', '=', 'pas.fk_periodo')
            ->join("srh.sig_cargo as sc", 'sc.id', '=', 'ss.fk_id_cargo')
            ->LeftJoin("servidor_comissao", 'servidor_comissao.fk_servidor', '=', 'ss.id_servidor')
            ->LeftJoin('comissao', 'servidor_comissao.fk_comissao', '=', 'comissao.id')
            ->select(
                'pas.fk_servidor',
                'sc.abreviacao as sigla_cargo',
                'ss.nome',
                'ss.cargo',
                'ss.matricula',
                'servidor_comissao.fk_comissao',
                'comissao.numero_comissao'
            )
            ->selectRaw('false as loading')
            ->whereNull('servidor_comissao.deleted_at')
            ->groupBy([
                'ss.nome',
                'pas.id',
                'pas.fk_servidor',
                'sc.abreviacao',
                'ss.cargo',
                'ss.matricula',
                'servidor_comissao.fk_comissao',
                'comissao.numero_comissao'
            ])
            ->orderBy('ss.nome')->distinct();

        if (isset($p->periodo_avaliacao)) {
            $sql->where('pas.fk_periodo', $p->periodo_avaliacao);
        }

        if (isset($p->cargo_comissao)) {
            $sql->where('ss.fk_id_cargo', $p->cargo_comissao);
        }

        $v = $sql->paginate(50);
        //if (!count($v->toArray()) > 0)
        if ($v->isEmpty() || (isset($v->data) && count($v->data) > -1))
            return response()->json(['mensagem' => 'Erro ao carregar os parametros da pesquisa.']);
        return response()->json($v);
    }

    // public static function vincularServidoresGrid(stdClass $p): JsonResponse
    // {
    //     $sql = DB::table('processo_avaliacao_servidor as pas')
    //         ->join('periodos_processo as pp', 'pp.id', '=', 'pas.fk_periodo')
    //         ->join("srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
    //         ->LeftJoin("seguranca.usuario as su", 'su.id', '=', 'pas.fk_avaliador')
    //         ->LeftJoin("policia.unidade as u", 'u.id', '=', 'pas.fk_unidade')
    //         ->join("srh.sig_cargo as sc", 'sc.id', '=', 'ss.fk_id_cargo')
    //         ->LeftJoin("servidor_comissao", 'servidor_comissao.fk_servidor', '=', 'ss.id_servidor')
    //         ->LeftJoin('comissao', 'servidor_comissao.fk_comissao', '=', 'comissao.id')
    //         ->select(
    //             'pas.id as id_processo_avaliacao',
    //             'pas.fk_servidor',
    //             'sc.abreviacao as sigla_cargo',
    //             'ss.nome',
    //             'ss.cargo',
    //             'ss.matricula',
    //             'servidor_comissao.fk_comissao'
    //         )
    //         ->whereNull('servidor_comissao.deleted_at')
    //         ->groupBy([
    //             'ss.nome',
    //             'pas.id',
    //             'pas.fk_servidor',
    //             'sc.abreviacao',
    //             'ss.cargo',
    //             'ss.matricula',
    //             'servidor_comissao.fk_comissao'
    //         ])
    //         ->orderBy('ss.nome')->distinct();

    //     if (isset($p->periodo_avaliacao)) {
    //         $sql->where('pas.fk_periodo', $p->periodo_avaliacao);
    //     }

    //     if (isset($p->cargo_comissao)) {
    //         $sql->where('ss.fk_id_cargo', $p->cargo_comissao);
    //     }

    //     $v = $sql->paginate(50);
    //     //if (!count($v->toArray()) > 0)
    //     if ($v->isEmpty() || (isset($v->data) && count($v->data) > -1))
    //         return response()->json(['mensagem' => 'Erro ao carregar os parametros da pesquisa.']);
    //     return response()->json($v);
    // }

    public static function delegadosAtivos()
    {
        return DB::table('srh.sig_servidor as ss')
            ->join("srh.sig_cargo as sc", 'sc.id', '=', 'ss.fk_id_cargo')
            ->orderBy('ss.nome')
            ->where('ss.status', 1)
            ->where('ss.fk_id_cargo', 24)
            ->get([
                'ss.id_servidor as id',
                'ss.nome as name'
            ]);
    }

    public static function servidorPolicial(stdClass $p): Collection
    {
        // Os campos que serão aplicados no Select
        $select = [
            'id_servidor as id',
            DB::raw("UPPER(CONCAT('(', cargo.abreviacao, ') ' , servidor.nome)) as cargo_e_nome"),
            DB::raw('UPPER(cargo.abreviacao) as cargo'),
            DB::raw('UPPER(servidor.nome) as nome'),
            'sit.nome as situacao',
        ];

        // Os cargos que serão usados para montar o combo. Apenas cargos de policiais.
        $cargosPermitidos = [
            //19, // Auxiliar tecnico de Policia Civil
            //38, // Motorista Policial
            24, // Delegado de Polícia
            //27, // Escrivão de Polícia
            //34, // Investigador de Polícia
            //40, // Papiloscopista
            //42, // Perito Policial
        ];

        // Situações Permitidas
        $situacoesPermitidas = [
            1,  // Em Atividade
            //36, // Apto ao trabalho
            //49, // Férias Interrompida
        ];

        $sql = DB::connection('conexao_srh')
            ->table('srh.sig_servidor as servidor')
            ->join('srh.sig_cargo as cargo', 'cargo.id', 'servidor.fk_id_cargo')
            //->leftJoin('srh.sig_situacao_servidores as situacao', 'situacao.fk_servidor', 'servidor.id_servidor')
            ->leftJoin('srh.sig_situacao_servidores as situacao', function ($join) use ($situacoesPermitidas){
                $join->on('servidor.id_servidor', '=', 'situacao.fk_servidor')
                    ->where(function($query) use ($situacoesPermitidas){
                        $query->whereIn('situacao.fk_situacao', $situacoesPermitidas);
                    })
                    ->where(function ($query){
                        $query->where('situacao.dt_termino', '>=', now());
                    });
            })
            ->whereNull('situacao.fk_servidor') // A condição para incluir servidores sem situações

            ->leftJoin('srh.sig_situacao_servidor as sit', 'sit.id', 'situacao.fk_situacao')
            ->whereIn('cargo.id', $cargosPermitidos)
            ->orderBy('servidor.nome')
            ->select($select);

            if(isset($p->nome))
                //$sql->whereRaw('servidor.nome', 'ilike', "%$p->nome%");
                $sql->whereRaw("public.sem_acento(servidor.nome) ilike public.sem_acento('%$p->nome%')");

        return $sql->get();
    }

}
