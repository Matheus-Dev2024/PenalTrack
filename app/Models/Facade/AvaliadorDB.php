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
        $listaAvaliador = DB::table('seguranca.usuario as u')
            ->join('seguranca.usuario_sistema as us', 'u.id', '=', 'us.usuario_id')
            ->join('eprobatorio.usuario_avaliador_intermediario as uai', 'u.id', '=', 'uai.usuario_cadastrado')
            ->join('eprobatorio.usuario_avalia_unidades as uau', 'u.id', '=', 'uau.usuario_id')
            //->join('eprobatorio.usuario_avalia_unidades as uau', 'u.id', '=', 'uau.usuario_id') // Descomentar esta linha para mostrar somente avaliadores com unidades para avaliar
            ->select('u.id', 'u.nome', 'u.email', 'u.status')
            ->where('us.sistema_id', '=', 56)
            ->where('uai.usuario_cadastrou', '=', $usuarioLogado->id_usuario)
            ->groupBy('u.id')
            ->orderBy('u.id')
            ->get();

        return $listaAvaliador;
    }

    public static function comboAvaliadorServidor()
    {
        $policia = config('database.connections.conexao_banco_unico.schema');
        return DB::table('processo_avaliacao_servidor as pas')
            ->LeftJoin("$policia.seguranca.usuario as su", 'su.id', '=', 'pas.fk_avaliador')
            ->orderBy('su.nome')
            ->distinct()
            ->whereNotNull('pas.fk_avaliador')
            ->get([
                'pas.fk_avaliador as id',
                'su.nome as name'
            ]);
    }
}
