<?php

namespace App\Models\Regras;

use App\Models\Entity\UsuarioAvaliaServidores;

class UsuarioAvaliaServidorRegras
{
    public static function salvar($dados)
    {
        UsuarioAvaliaServidores::create([
            'usuario_id' => $dados->usuario_id,
            'servidor_id' => $dados->servidor_id,
            'fk_processo_avaliacao' => $dados->processo_avaliacao
        ]);
    }
    
}