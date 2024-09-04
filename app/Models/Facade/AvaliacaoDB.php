<?php

namespace App\Models\Facade;

use App\Models\Entity\AvaliacaoServidor;
use App\Models\Entity\ProcessoAvaliacaoServidor;
use App\Models\Entity\Servidor;
use App\Models\Entity\ServidoresAvaliadosIndividualmente;
use App\Models\Entity\Usuario as EntityUsuario;
use App\Models\Entity\UsuarioAvaliaUnidades;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PoliciaCivil\Seguranca\Models\Entity\Usuario;
use stdClass;

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
        return AvaliacaoServidor::where('fk_processo_avaliacao_servidor', $processo_id)->where('fk_servidor', $servidor_id)->get();
    }


    public static function gridArquivos(stdClass $p)
    {
        //return $p;
        $sql = DB::table('arquivo_avaliacao_servidor as arquivo')
            ->join('tipo_arquivo as tipo', 'tipo.id', 'arquivo.fk_tipo_arquivo')
            ->where('arquivo.fk_processo_avaliacao_servidor', $p->processo_avaliacao_id)
            ->where('arquivo.fk_servidor', $p->servidor_id)
            ->select([
                'arquivo.id',
                'nome_arquivo',
                'descricao',
                'tipo.nome as tipo'
            ]);

        return $sql->get();
    }

    public static function getListaServidoresDoProcessoAvaliacao(stdClass $p): Collection
    {
        $usuario_id = Auth::user()->id;
        //$usuario_id = 587;

        // Lista de Unidades que o servidor logado deverá Avaliar
        $unidades_do_avaliador = UsuarioAvaliaUnidades::where('usuario_id', $usuario_id)->select('unidade_id');
        // $unidades_do_avaliador = UsuarioAvaliaUnidades::unidadesParaServidorAvaliar($usuario_id);

        // Lista com todos os processos que serão Avaliados Individualmente
        $processos_avaliados_individualmente = ServidoresAvaliadosIndividualmente::where('usuario_id', $usuario_id)->select('fk_processo_avaliacao_servidor');

        $servidores_avaliados_individualmente = ProcessoAvaliacaoServidor::WhereIn('id', $processos_avaliados_individualmente);

        $servidores_avaliados_por_unidade = ProcessoAvaliacaoServidor::whereIn('fk_unidade', $unidades_do_avaliador);

        $servidores_avaliados = $servidores_avaliados_individualmente->union($servidores_avaliados_por_unidade);

        //no select abaixo, transformamos o resultado da união entre as consultas em uma tabela "servidores_avaliados" para realizarmos os joins etc...;
        //e posteriormente utilizamos o mergeBindings+getQuery pra exibir com os mesmos parametros das consultas anteriores.


        $resultados = DB::table(DB::raw("({$servidores_avaliados->toSql()}) as servidores_avaliados"))
            ->mergeBindings($servidores_avaliados->getQuery()) // É importante passar o query builder aqui
            ->join("eprobatorio.processo_avaliacao_servidor as pas", "servidores_avaliados.id", 'pas.id')
            ->join("eprobatorio.periodos_processo as pp", "servidores_avaliados.fk_periodo", "pp.id")
            ->join("policia.unidade as uni", "servidores_avaliados.fk_unidade", "uni.id")
            ->join("srh.sig_servidor as ss", "ss.id_servidor", "servidores_avaliados.fk_servidor")
            ->join("srh.sig_cargo as c", "c.id", "=", "ss.fk_id_cargo")
            ->join("processo_situacao_servidor as pss", "pss.id", "=", "servidores_avaliados.status")
            ->select(
                'ss.nome as nome_servidor',
                'ss.id_servidor as servidor_id',
                'pp.nome as periodo',
                'pp.id as fk_periodo',
                'uni.nome as unidade',
                'pss.nome as situacao',
                'c.abreviacao as cargo',
                'pss.id as status',
                'pas.id as processo_avaliacao_id'
            )
            ->orderBy("ss.nome");

        $dados = $resultados->get();
        //filtro deve ser executado depois do get()
        if (isset($p->status)) {
            $dados = $dados->where('status', '=', $p->status);
        }

        if (isset($p->periodo)) {
            $dados = $dados->where('fk_periodo', '=', $p->periodo);
        }

        return $dados;
    }
    
    public static function comboProcesso(): Collection
    {
        return DB::table('processo_avaliacao')
            ->orderBy('descricao')
            ->whereNull('deleted_at')
            ->get([
                DB::raw('UPPER(descricao) as text'),
                'id as value',
            ]);
    }

    public static function comboStatus(): Collection
    {
        return DB::table('processo_situacao_servidor')
            ->orderBy('id')
            ->get([
                DB::raw('UPPER(nome) as text'),
                'id as value',
            ]);
    }

    public static function diasTrabalhadosPorPeriodo($dt_inicio, $dt_termino, $servidor_id)
    {
       $dados = DB::select("SELECT * FROM srh.sp_info_servidor_estagio_probatorio('$dt_inicio'::date, '$dt_termino'::date, $servidor_id)");
       return $dados;
    }

    public static function minhasAvaliacoes($usuario_id)
    {
        $usuario = EntityUsuario::where('id', $usuario_id)->first();
        
        $servidor = Servidor::where('cpf', $usuario->cpf)->first();
        return DB::table('processo_avaliacao_servidor as pas')
        ->join("periodos_processo as pp", "pas.fk_periodo", "pp.id")
        ->where('pas.fk_servidor', $servidor->id_servidor)
        ->orderBy('pp.nome')
        ->get([
            'pas.id',
            'pp.nome as periodo',
            DB::raw("TO_CHAR(pas.dt_inicio, 'DD/MM/YYYY') AS dt_inicio"),
            DB::raw("TO_CHAR(pas.dt_termino, 'DD/MM/YYYY') AS dt_termino"),
            'pas.fk_servidor',
            'pas.status',
            'pas.data_recurso'
        ]);
    }

    public static function getProcessoAvaliacaoServidor($processo_id)
    {
        return DB::table('processo_avaliacao_servidor as pas')
        ->join("periodos_processo as pp", "pas.fk_periodo", "pp.id")
        ->join("processo_situacao_servidor as pss", "pss.id", "pas.status")
        ->where('pas.id', $processo_id)
        ->first([
            'pas.id',
            'pp.nome as periodo',
            'pas.ciente_avaliado',
            'pas.status',
            'pss.nome as situacao'
        ]);
    }
}
