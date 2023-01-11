<?php

namespace App\Models\Regras;

use App\Models\Entity\ProcessoAvaliacao;
use App\Models\Entity\ProcessoAvaliacaoServidor;
use App\Models\Facade\AvaliacaoDB;
use App\Models\Facade\ProcessoAvaliacaoDB;
use Illuminate\Http\Request;

class ProcessoAvaliacaoRegras
{
    public static function salvar(Request $dados)
    {
        $processo = ProcessoAvaliacao::create([
            'descricao' => $dados->descricao,
            'dt_inicio_avaliacao' => $dados->dt_inicio_avaliacao,
            'dt_termino_avaliacao' => $dados->dt_termino_avaliacao,
            'dt_inicio_estagio' => $dados->dt_inicio_estagio,
            'ref_inicio' => $dados->ref_inicio,
            'ref_termino' => $dados->ref_termino,
            'instrucao' => $dados->instrucao
        ]);

        foreach ($dados->servidor as $id_servidor) {
            ProcessoAvaliacaoServidor::create([
                'fk_processo_avaliacao' => $processo->id,
                'fk_servidor' => $id_servidor

            ]);
        }

        return $processo;
    }


    public static function editar($id)
    {
        return ProcessoAvaliacao::find($id);
    }

    public static function alterar(Request $dados)
    {
        $processo = ProcessoAvaliacao::find($dados->id);
        $processo->descricao = $dados->descricao;
        $processo->dt_inicio_avaliacao = $dados->dt_inicio_avaliacao;
        $processo->dt_termino_avaliacao = $dados->dt_termino_avaliacao;
        $processo->dt_inicio_estagio = $dados->dt_inicio_estagio;
        $processo->ref_inicio = $dados->ref_inicio;
        $processo->ref_termino = $dados->ref_termino;
        $processo->instrucao = $dados->instrucao;
        $processo->save();
    }

    public static function remover($id_processo_avaliacao)
    {
        $processo = ProcessoAvaliacaoServidor::find($id_processo_avaliacao);
        $processo->delete();
    }

    public static function excluir($id_periodo)
    {
        $processo = ProcessoAvaliacao::find($id_periodo);
        $processo->delete();
    }

    public static function atualizaSituacaoServidor($p)
    {
        $notaTotalDoServidor = ProcessoAvaliacaoDB::getNotaTotalServidor($p->processo_avaliacao_id, $p->servidor_id);

        ProcessoAvaliacaoServidor::where('fk_processo_avaliacao', $p->processo_avaliacao_id)
                                ->where('fk_servidor', $p->servidor_id)
                                ->update([
                                    'dias_ausencia' => $p->dias_ausencia,
                                    'dias_trabalhados' => $p->dias_trabalhados,
                                    'dias_prorrogados' => $p->dias_prorrogados,
                                    'nota_total' => $notaTotalDoServidor,
                                    'status' => 2
                                ]);
    }

    
}
