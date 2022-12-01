<?php

namespace App\Models\Regras;


use App\Models\Entity\FatorAvaliacaoItem;

class FatorAvaliacaoItemRegras
{
    public static function salvar($dados)
    {
        
        FatorAvaliacaoItem::create([
            'pergunta' => $dados->pergunta,
            'fk_fator_avaliacao' => $dados->fator_avaliacao
            
        ]);
    }

    public static function alterar($dados)
    {
        $fator = FatorAvaliacaoItem::find($dados->id);
        $fator->fk_fator_avaliacao = $dados->fator_avaliacao;
        $fator->pergunta = $dados->pergunta;
        $fator->save();
    }

    public static function excluir($id)
    {
        $fator = FatorAvaliacaoItem::find($id);
        $fator->delete();

    }
}
