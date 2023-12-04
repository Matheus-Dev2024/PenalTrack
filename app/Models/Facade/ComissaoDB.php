<?php

namespace App\Models\Facade;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
}