<?php

namespace App\Models\Facade;

use App\Models\Entity\FatorAvaliacao;

class FatorAvaliacaoDB
{
    public static function listarFatorAvaliacao()
    {
        return FatorAvaliacao::all();
    }

    public static function getFormularioAvaliacao()
    {
        $formulario = FatorAvaliacao::all();

        foreach($formulario as $fator) {
            $fator->itens;
        }

        return $formulario;
    }
}
