<?php

namespace App\Models\Regras;

use App\Models\Entity\ProcessoAvaliacao;
use App\Models\Entity\ProcessoAvaliacaoServidor;
use App\Models\Facade\AvaliacaoDB;
use App\Models\Facade\ProcessoAvaliacaoDB;
use Exception;
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
                    'instrucao' => $dados->instrucao
                ]);
    }

    public static function salvarProcessoAvaliacaoServidor($dados, $novoProcesso)
    {
        //pega todos os servidores que farão estágio, a partir da data início e término da referência
        $servidoresDoProcesso = ProcessoAvaliacaoDB::listarServidoresGrid($dados->ref_inicio, $dados->ref_termino);

        foreach($servidoresDoProcesso as $servidor) {
            //para cada servidor, localizar a sua unidade de avaliação
            self::salvarProcessoAvaliacaoServidorIndividual($novoProcesso, $servidor->servidor);
        }
    }
    
    public static function salvarProcessoAvaliacaoServidorIndividual(ProcessoAvaliacao $processo, Int $id_servidor)
    {
        //calcular os dias de estágio com base na data início e término de avaliação
        $data1 = new \DateTime($processo->dt_inicio_avaliacao);
        $data2 = new \DateTime($processo->dt_termino_avaliacao);
        $diferenca = $data1->diff($data2);
        $diasDeEstagio = $diferenca->days;
        
        //pega o dia de trabalho programado (verificar se esse campo ainda é necessário)
        $diasDeTrabalhoProgramado = 180;
        
        $info = DB::select("SELECT * FROM srh.sp_info_servidor_estagio_probatorio('$processo->dt_inicio_avaliacao', '$processo->dt_termino_avaliacao', $id_servidor)");
            if($info) {
                ProcessoAvaliacaoServidor::create([
                    'fk_processo_avaliacao' => $processo->id,
                    'fk_servidor' => $id_servidor,
                    'fk_unidade' => $info[0]->fk_id_unidade,
                    'dias_estagio' => $diasDeEstagio,
                    'dias_trabalho_programado' => $diasDeTrabalhoProgramado
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
