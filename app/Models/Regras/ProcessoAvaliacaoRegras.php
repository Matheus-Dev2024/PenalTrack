<?php

namespace App\Models\Regras;

use App\Models\Entity\PeriodoProcessoAvaliacao;
use App\Models\Entity\ProcessoAvaliacao;
use App\Models\Entity\ProcessoAvaliacaoServidor;
use App\Models\Facade\ProcessoAvaliacaoDB;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcessoAvaliacaoRegras
{
    public static function salvar(Request $dados)
    {
        $novoProcesso = self::salvarProcessoAvaliacao($dados);
        self::salvarProcessoAvaliacaoServidor($dados, $novoProcesso);
        return $novoProcesso;
    }

    public static function salvarProcessoAvaliacao($dados)
    {
        return ProcessoAvaliacao::create([
            'descricao' => $dados->descricao,
            'dt_inicio_avaliacao' => $dados->dt_inicio_avaliacao,
            'dt_termino_avaliacao' => $dados->dt_termino_avaliacao,
            'dt_inicio_estagio' => $dados->dt_inicio_estagio,
            'ref_inicio' => $dados->ref_inicio,
            'ref_termino' => $dados->ref_termino,
            'instrucao' => $dados->instrucao,
            'fk_periodo_processo' => $dados->fk_periodo_processo
        ]);
    }

    public static function salvarProcessoAvaliacaoServidor($dados, $novoProcesso)
    {
        //pega todos os servidores que farão estágio, a partir da data início e término da referência
        $servidoresDoProcesso = ProcessoAvaliacaoDB::listarServidoresGrid($dados->ref_inicio, $dados->ref_termino);

        foreach ($servidoresDoProcesso as $servidor) {
            //para cada servidor, localizar a sua unidade de avaliação
            self::salvarProcessoAvaliacaoServidorIndividual($novoProcesso, $servidor->servidor, $servidor->data_exercicio);
        }
    }

    public static function salvarProcessoAvaliacaoServidorIndividual(ProcessoAvaliacao $processo, int $id_servidor, $data_exercicio)
    {
        $periodoProcesso = PeriodoProcessoAvaliacao::find($processo->fk_periodo_processo);

        //calcular os dias de estágio com base na data início e término de avaliação
        $dataInicio = new DateTime(date('Y-m-d', strtotime("+{$periodoProcesso->dia_inicial} days", strtotime($data_exercicio))));
        $dataFinal = new DateTime(date('Y-m-d', strtotime("+{$periodoProcesso->dia_final} days", strtotime($data_exercicio))));

        $dataInicioString = $dataInicio->format('d-m-y');
        $dataFinalString = $dataFinal->format('d-m-y');

        $diferenca = $dataInicio->diff($dataFinal);
        $diasDeEstagio = $diferenca->days;

        //pega o dia de trabalho programado (verificar se esse campo ainda é necessário)
        $diasDeTrabalhoProgramado = 180;
        
        //para utilizar a função 'srh.sp_info_servidor_estagio_probatorio', o formato da data precisa ser "d-m-y"
        $info = DB::select("SELECT * FROM srh.sp_info_servidor_estagio_probatorio('$dataInicioString', '$dataFinalString', $id_servidor)");
        if ($info) {
            ProcessoAvaliacaoServidor::create([
                'fk_processo_avaliacao' => $processo->id,
                'fk_servidor' => $id_servidor,
                'fk_unidade' => $info[0]->fk_id_unidade,
                'dias_estagio' => $diasDeEstagio,
                'dias_trabalho_programado' => $diasDeTrabalhoProgramado,
                'dias_ausencia' => $info[0]->dias_ausencia
            ]);
        }
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
                'parecer_avaliador' => $p->parecer_avaliador,
                'status' => 2
            ]);
    }


}
