<?php

namespace App\Models\Regras;
use Barryvdh\DomPDF\Facade\Pdf;

class ImpressaoRegras
{
    public static function imprimirAvaliacao (\stdClass $p){
        $avaliacaocontroller = new AvaliacaoServidorRegras();
        $json = $avaliacaocontroller->informacao($p);
        $dados = json_decode($json->getContent(), false);
        //return $dados;
        $pdf = Pdf::loadView('impressao.avaliacao_servidor_pdf', compact('dados'));

        return $pdf->setPaper('a4')->stream('documento.pdf', ['Attachment' => true]);
    }
}
