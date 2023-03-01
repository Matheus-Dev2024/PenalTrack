<?php

namespace App\Models\Facade;

use Illuminate\Support\Facades\DB;

class TipoArquivoDB
{

    public static function combo()
    {
        return DB::table('tipo_arquivo as tipo')
                ->select([
                    'tipo.nome as text',
                    'tipo.id as value'
                ])
                ->get();
    }
}
