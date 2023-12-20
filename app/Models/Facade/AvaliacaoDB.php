<?php

namespace App\Models\Facade;

use App\Models\Entity\AvaliacaoServidor;
use App\Models\Entity\ServidoresAvaliadosIndividualmente;
use App\Models\Entity\UsuarioAvaliaUnidades;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        return AvaliacaoServidor::where('fk_processo_avaliacao', $processo_id)->where('fk_servidor', $servidor_id)->get();
    }


    public static function gridArquivos(\stdClass $p){
        //return $p;
        $sql = DB::table('arquivo_avaliacao_servidor as arquivo')
            ->join('tipo_arquivo as tipo', 'tipo.id', 'arquivo.fk_tipo_arquivo')
            ->where('arquivo.fk_processo_avaliacao', $p->processo_avaliacao_id)
            ->where('arquivo.fk_servidor', $p->servidor_id)
            ->select([
                'arquivo.id',
                'nome_arquivo',
                'descricao',
                'tipo.nome as tipo'
            ]);

        return $sql->get();
    }

    public static function getListaServidoresDoProcessoAvaliacao(\stdClass $p) :\illuminate\http\JsonResponse
    {
        $usuario_id = Auth::user()->id;
        //$usuario_id = 587;

        $select = [
            'pa.id as processo_avaliacao_id','pa.dt_inicio_avaliacao',
            'pa.dt_termino_avaliacao',
            DB::raw('SUBSTRING(pa.descricao, 1, 23) as nome_processo'),
            'ss.nome',
            'ss.id_servidor as id',
            'ss.matricula',
            'c.abreviacao as cargo',
            //'unidadee.nome as unidade',
            //'unidade.id as unidade_id',
            'pss.nome as situacao',
            'pas.nota_total',
            'pas.status'
        ];

        // Lista de Unidades que o servidor logado deverá Avaliar
        $unidades_do_avaliador = UsuarioAvaliaUnidades::where('usuario_id', $usuario_id);

        // Lista com todos os servidores que serão Avaliados Individualmente
        $servidores_avaliados_individualmente = ServidoresAvaliadosIndividualmente::all();

        //Lista com todos os Servidores que serão avaliados individualmente pelo usuário logado
        $servidores_avaliados_por_este_usuario = $servidores_avaliados_individualmente->where('usuario_id', $usuario_id)
            ->pluck('servidor_id')->toArray();

        $sql = DB::table("eprobatorio.processo_avaliacao as pa")
        ->join("eprobatorio.processo_avaliacao_servidor as pas", "pas.fk_processo_avaliacao", "pa.id")
        ->join("srh.sig_servidor as ss", "ss.id_servidor", "pas.fk_servidor")
        ->join("srh.sig_cargo as c", "c.id", "=", "ss.fk_id_cargo")
        ->join("processo_situacao_servidor as pss", "pss.id", "=", "pas.status")
        ->orderBy("pa.id");

        // Select que retorna os servidores que serão avaliados pelo usuário logado
        $servidoresPorAvaliador = clone $sql;
        $servidoresPorAvaliador->join("usuario_avalia_servidores as uas", 'uas.servidor_id', "ss.id_servidor")
            ->whereIn('uas.servidor_id', $servidores_avaliados_por_este_usuario);
        if (isset($p->descricao)) {
            $servidoresPorAvaliador->where('pa.id', $p->descricao);
        }
        $servidoresPorAvaliador = $servidoresPorAvaliador->select($select);

        // Verifica a unidade em que os servidorres trabalharam mais tempo
        $servidoresPorAvaliador->each(function($servidor){
            $unidade = DB::select(DB::raw("select fk_unidade, unidade from srh.sp_lotacao_com_maior_tempo_de_servico_por_periodo(:inicio, :termino, :id)"),[
                'inicio' => $servidor->dt_inicio_avaliacao,
                'termino' => $servidor->dt_termino_avaliacao,
                'id' => $servidor->id
            ]);
            $servidor->unidade_id = $unidade[0]->fk_unidade;
            $servidor->unidade = $unidade[0]->unidade;
        });

        // Select que retorna os servidores das unidades que o usuário logado deve avaliar, exceto os listados para serem avaliados individualmente por algum usuário.
        $servidoresPorUnidade = clone $sql;
        $servidoresPorUnidade->join(
                "policia.unidade as unidade",
                "unidade.id","=",
                "pas.fk_unidade")
            ->whereIn('unidade.id', $unidades_do_avaliador->pluck('unidade_id'))
            ->whereNotIn('ss.id_servidor', $servidores_avaliados_individualmente->pluck('servidor_id'))
            ->select($select);
            if (isset($p->descricao)) {
                $servidoresPorUnidade->where('pa.id', $p->descricao);
            }

        // Une os servidores que serão avaliados expecificamente pelo usuário logado com os servidores das unidades que deverão ser avaliadas pelo usuário logado.
        $servidores = $servidoresPorAvaliador->union($servidoresPorUnidade)->get()->sortBy('nome');

        return response()->json($servidores->values()->all());
    }

    public static function combo(){
        return DB::table('processo_avaliacao')
            ->orderBy('descricao')
            ->whereNull('deleted_at')
            ->get([
                'id as value',
                'descricao as text'
            ]);
    }

}
