<?php

namespace App\Models\Regras;

use App\Models\Entity\Comissao;
use App\Models\Entity\ParecerComissao;
use App\Models\Entity\ServidorComissao;
use Exception;
use Illuminate\Support\Facades\DB;

class ComissaoRegras
{
    public static function alterar($dados)
    {
        $regras = [
            'presidente' => 'required',
            'tipo_comissao' => 'required',
            'primeiro_membro' => 'required',
            'segundo_membro' => 'required',
        ];

        $dados->validate($regras);

        $comissao = Comissao::find($dados->id);
        //lógica para validar o cargo da comissao, considerando que a comissão só pode avaliar outro cargo se ela estiver vazia.
        if($comissao->fk_cargo_comissao != $dados->tipo_comissao) { //verifica se o cargo da comissao esta sendo trocado
            ServidorComissao::where('fk_comissao', $comissao->id)->delete();

        }

        $comissao->presidente = $dados->presidente;
        $comissao->fk_cargo_comissao = $dados->tipo_comissao;
        $comissao->primeiro_membro = $dados->primeiro_membro;
        $comissao->segundo_membro = $dados->segundo_membro;
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
