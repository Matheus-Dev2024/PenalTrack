<?php

namespace App\Http\Controllers;

use App\Http\Resources\AvaliadoResource;
use App\Models\Entity\ArquivoAvaliacaoServidor;
use App\Models\Entity\FatorAvaliacao;
use App\Models\Entity\ProcessoAvaliacaoServidor;
use App\Models\Facade\AvaliacaoDB;
use App\Models\Regras\AvaliacaoServidorRegras;
use App\Models\Regras\ImpressaoRegras;
use App\Models\Regras\ProcessoAvaliacaoRegras;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AvaliacaoController extends Controller
{
    public function formulario()
    {
        $formulario = FatorAvaliacao::all();

        foreach ($formulario as $fator) {
            $fator->itens;
        }

        return response()->json($formulario);
    }

    public function info(Request $request)
    {
        $p = (object)$request->validate([
            'processo_id' => 'required',
            'servidor_id' => 'required'
        ]);
        return AvaliacaoServidorRegras::informacao($p);
    }

    public function store(Request $request)
    {

        //Inicia o Database Transaction
        DB::beginTransaction();
        try {
            $params = (object)$request->all();
            AvaliacaoServidorRegras::adicionarNotas($params);
            ProcessoAvaliacaoRegras::atualizaSituacaoServidor($params);
            DB::commit();
            return response()->json(['message' => 'Avaliação enviada com sucesso.']);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => 'Opa, algo aconteceu. ' . $ex->getMessage()], 500);
        }
    }

    public function getServidoresAvaliacaoCorrente(Request $request)
    {
        $p = (object)$request->all();
        $dados = AvaliacaoDB::getListaServidoresDoProcessoAvaliacao($p);
        return response(AvaliadoResource::collection($dados), 200);
    }

    // Retorna os arquivos de uma Avaliação
    public function GridArquivos(Request $request)
    {
        $p = (object)$request->validate([
            'servidor_id' => 'required',
            'processo_avaliacao_id' => 'required'
        ]);
        return response()->json(AvaliacaoServidorRegras::gridArquivos($p));
    }

    // Faz o upload de um arquivo para o banco
    public function uploadArquivo(Request $request)
    {
        $p = (object)$request->all();
        DB::beginTransaction();
        try {
            AvaliacaoServidorRegras::uploadArquivo($request);
            DB::commit();
            return response()->json([
                'message' => 'Arquivo armazenado com sucesso'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage()], 500);
            } else {
                return response()->json(['message' => 'Falha ao gravar arquivos'], 500);
            }
        }
    }

    // Exclui um arquivo específico através do ID
    public function ExcluirArquivo(ArquivoAvaliacaoServidor $arquivo)
    {
        DB::beginTransaction();
        try {
            AvaliacaoServidorRegras::excluirArquivo($arquivo);
            DB::commit();
            return response()->json([
                'message' => "Arquivo excluído com sucesso!"
            ]);
        } catch (Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage()], 500);
            } else {
                return response()->json(['message' => 'Falha ao excluir arquivo'], 500);
            }
        }
    }

    public function comboProcesso(): array
    {
        $comboProcesso = AvaliacaoDB::comboProcesso();
        return compact('comboProcesso');
    }

    public function comboStatus(): Collection
    {
        return AvaliacaoDB::comboStatus();
    }


    //retorna um arquivo para ser baixado
    public function exibirArquivo(Request $request)
    {
        $p = (object)$request->all();
        return AvaliacaoServidorRegras::exibirArquivo($p);
    }

    public function minhasAvaliacoes($usuario_id)
    {
        $avaliacoes = AvaliacaoDB::minhasAvaliacoes($usuario_id);
        return $avaliacoes;
    }

    public function imprimirAvaliacao($processo_id)
    {
        $servidor_id = ProcessoAvaliacaoServidor::where('id', $processo_id)->value('fk_servidor');
        
        $p = new \stdClass();
        $p->processo_id = $processo_id;
        $p->servidor_id = $servidor_id;
       
        return ImpressaoRegras::imprimirAvaliacao($p);
    }

    public function confirmarCienciaAvaliado(Request $request, $processo_id)
    {
        $processo_avaliacao_servidor = ProcessoAvaliacaoServidor::where('id', $processo_id)->first();
        if ($processo_avaliacao_servidor) {
            //verifica se o processo ja foi alguma vez concluído ou recusado
            if ($processo_avaliacao_servidor->status == 4 || $processo_avaliacao_servidor->status == 3) {
                return response()->json(['message' => 'Não é possível dar ciência em uma avaliação ja concluída ou recusada.'], 400);
            } 
            $processo_avaliacao_servidor->ciente_avaliado = $request->ciente_avaliado;
            $processo_avaliacao_servidor->status = 4; // 4 é o status de conclúido conforme tabela processo_situacao_servidor  
            $processo_avaliacao_servidor->save();
            return response()->json(['message' => 'Ciência da avaliação confirmada'], 200);
        }
        return response()->json(['message' => 'Erro ao dar ciência na avaliação'], 500);
    }
    public function recusarAvaliacao($processo_id)
    {
        $processo_avaliacao_servidor = ProcessoAvaliacaoServidor::where('id', $processo_id)->first();
        if ($processo_avaliacao_servidor) {
            //verifica se o processo ja foi alguma vez concluído ou recusado
            if ($processo_avaliacao_servidor->status == 4 || $processo_avaliacao_servidor->status == 3) {
                return response()->json(['message' => 'Não é possível recusar uma avaliação ja concluída ou recusada.'], 400);
            } 
            $processo_avaliacao_servidor->status = 3; // 3 é o status de processo com recurso
            $processo_avaliacao_servidor->data_recurso = Carbon::now();
            $processo_avaliacao_servidor->save();
            return response()->json(['message' => 'Avaliação Recusada, apresente o recurso em até 10 (dez) dias na DIRETORIA DE RECURSOS HUMANOS'], 200);
        }
    }
    
    public function getProcessoAvaliacaoServidor($processo_id)
    {
        $processo_avaliacao_servidor = AvaliacaoDB::getProcessoAvaliacaoServidor($processo_id);
        return $processo_avaliacao_servidor;
    }

}
