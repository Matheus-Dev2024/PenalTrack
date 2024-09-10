<?php

namespace App\Models\Facade;

use App\Models\Entity\Usuario;
use Illuminate\Support\Facades\DB;
use PoliciaCivil\Seguranca\Models\Entity\Usuario as EntityUsuario;

class UsuarioLocalDB
{
    public static function getUsuario($usuarioId)
    {
        return EntityUsuario::where('id', $usuarioId)
        ->where('excluido', false)
        ->first();
    }

    public static function getToken($token, $usuarioId)
    {
        return DB::connection('conexao_seguranca')
        ->table('seguranca.acesso_unico_usuario_sistema')
        ->where('token', $token)
        ->where('sistema_id', config('policia.codigo'))
        ->where('usuario_id', $usuarioId)
        ->first();
    }

    public static function isPermissaoSistema($usuarioId): bool
    {
        return DB::connection('conexao_seguranca')
        ->table("seguranca.usuario_sistema")
        ->where('usuario_id', $usuarioId)
        ->where('sistema_id', config('policia.codigo'))
        ->exists();
    }
}
