<?php

namespace App\Models\Facade;

use Illuminate\Support\Facades\DB;

class DocumentacaoDB
{   
    public static function grid()
    {
        $arquivo = DB::table('sig_documentacao_servidor')
            ->select([
                'arquivo',
            ])
            ->get();

        return $arquivo;
    }
    // public static function grid ()
    // {
    //     return Documentacao::all();
    // }
}