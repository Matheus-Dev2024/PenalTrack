<?php

namespace App\Models\Facade;

use App\Models\Entity\FatorAvaliacao;

class FatorAvaliacaoDB
{
    public static function listarFatorAvaliacao()
    {
        return FatorAvaliacao::all();
    }
}
