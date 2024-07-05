<?php

namespace App\Models\Regras;

use App\Models\Entity\Comissao;
use App\Models\Entity\ParecerComissao;

class ComissaoRegras
{
    public static function alterar($dados)
    {
        $comissao = Comissao::find($dados->id);
        $comissao->presidente = $dados->presidente;
        $comissao->save();
    }

    public static function salvarParecer($dados)
    {
        $parecer = ParecerComissao::where('fk_processo_avaliacao', $dados->processo_id)->first();
        if($parecer){
            $parecer->parecer = $dados->parecer;
            $parecer->save();
        }  else {
            $parecer = new ParecerComissao();
            $parecer->fk_processo_avaliacao = $dados->processo_id;
            $parecer->parecer = $dados->parecer;
            $parecer->save();
        }

    }
}
