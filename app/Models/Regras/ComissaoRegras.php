<?php

namespace App\Models\Regras;

use App\Models\Entity\Comissao;

class ComissaoRegras
{
    public static function alterar($dados)
    {
        $comissao = Comissao::find($dados->id);
        $comissao->presidente = $dados->presidente;
        $comissao->save();
    }
}
