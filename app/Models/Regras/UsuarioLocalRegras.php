<?php

namespace App\Models\Regras;

use Illuminate\Support\Facades\DB;
use PoliciaCivil\Seguranca\Models\Regras\UsuarioRegras;

class UsuarioLocalRegras extends UsuarioRegras
{

    public static function removerTokenUsuario($acessoTokenId)
    {
        DB::connection('conexao_seguranca')
            ->table('seguranca.acesso_unico_usuario_sistema')
            ->where('id', $acessoTokenId)
            ->delete();
    }
}
