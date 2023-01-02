<?php

namespace App\Models\Regras;
use Barryvdh\DomPDF\Facade\Pdf;

class ImpressaoRegras
{
    public static function imprimirAvaliacao (\stdClass $p){
        $avaliacaocontroller = new AvaliacaoServidorRegras();
        $json = $avaliacaocontroller->info($p);
        $dados = json_decode($json->getContent(), false);

        $pdf = Pdf::loadView('impressao.avaliacao_servidor_pdf', compact('dados'));

        return $pdf->setPaper('a4')->stream('documento.pdf');
    }
}
