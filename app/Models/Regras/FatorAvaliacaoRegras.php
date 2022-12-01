<?php

namespace App\Models\Regras;

use App\Models\Entity\FatorAvaliacao;

class FatorAvaliacaoRegras
{
    public static function salvar($dados)
    {
        FatorAvaliacao::create([
            'nome' => $dados->fator_avaliacao
        ]);
    }

    public static function alterar($dados)
    {
        $fator = FatorAvaliacao::find($dados->id);
        $fator->nome = $dados->fator_avaliacao;
        $fator->save();
    }

    public static function excluir($id)
    {
        $fator = FatorAvaliacao::find($id);
        $fator->delete();
    }
}
