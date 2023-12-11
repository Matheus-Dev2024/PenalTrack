<?php

namespace App\Models\Regras;

use App\Models\Entity\Servidor;
use App\Models\Entity\ServidorComissao;

class ServidorComissaoRegras
{
    public static function salvar($dados)
    {
        //caso o servidor ja tenha alguma comissão vinculada, este registro é deletado e é efetuado um novo registro
        $servidorNaComissao = ServidorComissao::where('fk_servidor', '=', $dados->fk_servidor)
                                                ->whereNull('deleted_at')
                                                ->first();
        if($servidorNaComissao) {
            $servidorNaComissao->delete();
        }

        $servidorComissao = new ServidorComissao;
        $servidorComissao->fk_servidor = $dados->fk_servidor;
        $servidorComissao->fk_comissao = $dados->fk_comissao;
        $servidorComissao->save();
    }

}