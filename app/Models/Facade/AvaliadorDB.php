<?php

namespace App\Models\Facade;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class AvaliadorDB
{
    public static function gerenciaBusca($request)
    {

        // $request['id'] ? self::UnidadesGrid($request['id']) : self::listarUnidades();

        if ($request['id']) {
            return self::UnidadesGrid($request['id']);
        } else {
            return self::listarUnidades();
        }
    }

    public static function listarUnidades()
    {

        $listaUnidades = DB::table('policia.unidade')
            ->select('id', 'nome')
            ->where('status', '=', 1)
            ->orderBy('nome')
            ->get();

        return $listaUnidades;
    }

    public static function UnidadesGrid($id)
    {

        $avaliadorUnidades = DB::table('eprobatorio.usuario_avalia_unidades as ua')
            ->join('seguranca.usuario as u', 'u.id', '=', 'ua.usuario_id')
            ->join('policia.unidade as uni', 'uni.id', '=', 'ua.unidade_id')
            ->select('ua.id as id', 'u.nome as usuario', 'uni.nome as unidade')
            ->where('u.id', '=', $id)
            ->get();

        return $avaliadorUnidades;
    }

    public static function PesquisaAvaliador(stdClass $usuarioLogado): Collection
        // usuario avalia unidades onde o id é igual ao 56(estágio probatório)
    {
        $fk_risp_logado = DB::table('policia.usuario_risp')
        ->where('fk_usuario', $usuarioLogado->id_usuario)
        ->value('fk_risp');

        $fk_diretoria_logado = DB::table('seguranca.usuario')
        ->where('id', $usuarioLogado->id_usuario)
        ->value('fk_unidade');

        $listaAvaliador = DB::table('seguranca.usuario as u')
            ->join('seguranca.usuario_sistema as us', 'u.id', '=', 'us.usuario_id')
            ->join('eprobatorio.usuario_cadastro_avaliador as uai', 'u.id', '=', 'uai.usuario_cadastrado')
            ->join('eprobatorio.usuario_avalia_unidades as uau', 'u.id', '=', 'uau.usuario_id')
            ->leftJoin('policia.usuario_risp as ur', 'u.id', '=', 'ur.fk_usuario')
            ->select('u.id', 'u.nome', 'u.email', 'u.status')
            ->where('us.sistema_id', '=', 56)
            ->where('uai.fk_risp', '=', $fk_risp_logado)
            ->orWhere('uai.fk_diretoria', '=', $fk_diretoria_logado)
            //->where('uai.usuario_cadastrou', '=', $usuarioLogado->id_usuario) // Exibe apenas os avaliadores que o usuario cadastrou
            ->groupBy('u.id')
            ->orderBy('u.id')
            ->get();

        return $listaAvaliador;
    }

    public static function comboAvaliadorServidor()
    {
        $avaliador_por_servidor = DB::table('usuario_avalia_servidores as uas')
            ->LeftJoin("seguranca.usuario as su", 'su.id', '=', 'uas.usuario_id')
            ->orderBy('su.nome')
            ->select([
                'uas.usuario_id as id',
                'su.nome as name'
            ]);
        $avaliador_por_unidade = DB::table('usuario_avalia_unidades as uau')
            ->LeftJoin("seguranca.usuario as su", 'su.id', '=', 'uau.usuario_id')
            ->orderBy('su.nome')
            ->select([
                'uau.usuario_id as id',
                'su.nome as name'
            ]);

        $avaliadores = $avaliador_por_unidade->union($avaliador_por_servidor)->get();
        return $avaliadores;
    }

    public static function carregarServidorPorPeriodo($periodo)
    {
        // $srh = config('database.connections.conexao_srh.schema');
        // $policia = config('database.connections.conexao_banco_unico.database');

        $servidoresPorPeriodo = DB::table('processo_avaliacao_servidor as pas')
            ->join("srh.sig_servidor as ss", 'ss.id_servidor', '=', 'pas.fk_servidor')
            ->join('periodos_processo as pp', 'pp.id', '=', 'pas.fk_periodo')
            ->join("srh.sig_cargo as sgc", 'sgc.id', '=', 'ss.fk_id_cargo')    
            ->select(
                'pas.id as id_processo_avaliacao_servidor',
                'pas.fk_servidor',
                'ss.nome',
                'sgc.abreviacao'
            )
            ->where('pas.fk_periodo', '=', $periodo)
            ->groupBy([
                'ss.nome',
                'pas.id',
                'pas.fk_servidor',
                'sgc.abreviacao'

            ])
            ->get();

        return $servidoresPorPeriodo;
    }
}
