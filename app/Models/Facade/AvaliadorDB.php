<?php

namespace App\Models\Facade;

use Illuminate\Support\Facades\DB;

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

    public static function PesquisaAvaliador()
    // usuario avalia unidades onde o id é igual ao 56(estágio probatório)
    {
        $listaAvaliador = DB::table('seguranca.usuario as u')
        ->join('seguranca.usuario_sistema as us', 'u.id', '=', 'us.usuario_id')
        ->join('eprobatorio.usuario_avalia_unidades as uau', 'u.id', '=', 'uau.usuario_id')
        ->select('u.id', 'u.nome', 'u.email','u.status')
        ->where('us.sistema_id', '=', 56)
        ->groupBy('u.id')
        ->orderBy('u.id')
        ->get();

        return $listaAvaliador;
    }



    
}
