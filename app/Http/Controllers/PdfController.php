<?php

namespace App\Http\Controllers;

use App\Models\Facade\ComissaoDB;
use App\Models\Facade\ProcessoAvaliacaoServidorDB;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function gerarRelatorioComissao($fk_servidor)
    {
        $dadosServidor = ProcessoAvaliacaoServidorDB::dadosServidorRelatorio($fk_servidor);
        $dadosAvaliacao = ProcessoAvaliacaoServidorDB::dadosAvaliacaoRelatorio($fk_servidor);
        // dd($dadosAvaliacao);
        $dadosItensAvaliacao = ProcessoAvaliacaoServidorDB::dadosItensAvaliacaoRelatorio($fk_servidor);

        //return compact('dadosServidor', 'dadosAvaliacao', 'dadosItensAvaliacao');
        //return view('impressao.relatorio_comissao', compact('dadosServidor', 'dadosAvaliacao', 'dadosItensAvaliacao'));
        $pdf = Pdf::loadView('impressao.relatorio_comissao', compact('dadosServidor', 'dadosAvaliacao', 'dadosItensAvaliacao'));
        $pdf->setPaper('a4', 'portrait')->setWarnings(false)->save('myfile.pdf');
        return $pdf->stream(
            'Solicitação de '
        );
    }

    public function imprimirParecerComissao($fk_servidor)
    {
        $dadosServidor = ProcessoAvaliacaoServidorDB::dadosServidorRelatorio($fk_servidor);
        $parecerServidor = ComissaoDB::carregarParecerServidor($fk_servidor);
        $comissaoServidor = ComissaoDB::carregarComissaoParecer($fk_servidor);
        //return view('impressao.parecer_comissao', compact('dadosServidor','parecerServidor'));
        $pdf = Pdf::loadView('impressao.parecer_comissao', compact('dadosServidor', 'parecerServidor', 'comissaoServidor'));
        $pdf->setPaper('a4', 'portrait')->setWarnings(false)->save('myfile.pdf');
        return $pdf->stream(
            'Solicitação de '
        );
    }

}
