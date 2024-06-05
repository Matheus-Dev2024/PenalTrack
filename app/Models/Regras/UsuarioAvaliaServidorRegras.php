<?php

namespace App\Models\Regras;

use App\Models\Entity\UsuarioAvaliaServidores;
use stdClass;

class UsuarioAvaliaServidorRegras
{
    public static function salvar(stdClass $dados)
    {
        UsuarioAvaliaServidores::create([
            'usuario_id' => $dados->usuario_id,
            'servidor_id' => $dados->servidor_id,
            'fk_processo_avaliacao_servidor' => $dados->processo_avaliacao_servidor
        ]);
    }

    public static function removerServidorAvaliadoIndividualmente($id)
    {
        //dd($id);
        $servidor = UsuarioAvaliaServidores::find($id);
        //dd($servidor);
        $servidor->delete();
    }
}

