<?php

namespace App\Models\Regras;

use App\Models\Entity\ArquivoAvaliacaoServidor;
use App\Models\Entity\AvaliacaoServidor;
use App\Models\Entity\ProcessoAvaliacaoServidor;
use App\Models\Facade\AvaliacaoDB;
use App\Models\Facade\FatorAvaliacaoDB;
use App\Models\Facade\ProcessoAvaliacaoDB;
use App\Models\Facade\ServidorDB;
use App\Models\Facade\TipoArquivoDB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class AvaliacaoServidorRegras
{
    public static function informacao(stdClass $p)
    {
        $formulario = FatorAvaliacaoDB::getFormularioAvaliacao();
        $processo = ProcessoAvaliacaoDB::getById($p->processo_id);
        $servidor = ServidorDB::info($p->servidor_id, $processo->processo_id, $processo->dt_inicio_avaliacao_en, $processo->dt_termino_avaliacao_en);
        $ausencias = ServidorDB::listaAusenciasPorPeriodo($p->servidor_id, $processo->dt_inicio_avaliacao_en, $processo->dt_termino_avaliacao_en);

        $notas = AvaliacaoDB::getNotasServidor($processo->processo_id, $p->servidor_id);

        $totalDasNotas = ProcessoAvaliacaoDB::getNotaTotalServidor($processo->processo_id, $p->servidor_id);

        $parecer = ProcessoAvaliacaoServidor::where('fk_processo_avaliacao', $p->processo_id)
            ->where('fk_servidor', $p->servidor_id)
            ->first()->parecer_avaliador;

        $impressao = false;

        $combo_tipo_arquivo = TipoArquivoDB::combo();

        if (isset ($notas[0])) $impressao = true;

        return response()->json([
            'combo_tipo_arquivo' => $combo_tipo_arquivo,
            'processo' => $processo,
            'formulario' => $formulario,
            'notas' => $notas,
            'total_notas' => $totalDasNotas,
            'servidor' => $servidor,
            'ausencias' => $ausencias,
            'habilitarimpressao' => $impressao,
            'parecer' => $parecer,
        ]);
    }

    public static function adicionarNotas($p)
    {
        self::validarForm($p);


        AvaliacaoServidor::where('fk_processo_avaliacao', $p->processo_avaliacao_id)
            ->where('fk_servidor', $p->servidor_id)
            ->delete();

        foreach ($p->notas as $item_id => $nota) {

            if (!empty($nota)) {

                AvaliacaoServidor::create([
                    'fk_processo_avaliacao' => $p->processo_avaliacao_id,
                    'fk_servidor' => $p->servidor_id,
                    'fk_fator_avaliacao_item' => $item_id,
                    'nota' => $nota
                ]);

            }
        }
    }

    public static function validarForm($p)
    {
        if (count($p->notas) == 0) {
            throw new Exception('É obrigatório registrar as notas antes de salvar o formulário.');
        }

        foreach ($p->notas as $indice => $nota) {
            if ($indice != 0 && empty($nota)) {
                throw new Exception('É obrigatório registrar a nota de todos os quesitos.');
            }
        }
    }

    public static function gridArquivos(stdClass $p)
    {
        return AvaliacaoDB::gridArquivos($p);
    }

    public static function uploadArquivo($p)
    {

        for ($i = 0; $i < $p->quantidade; $i++) {

            // Cria e instancia as variáveis dinâmicas
            $arquivo = "arquivo" . $i;
            $$arquivo = "arquivo" . $i;
            $descricao = "descricao" . $i;
            $$descricao = "descricao" . $i;
            $nome = "nome" . $i;
            $$nome = "nome" . $i;
            $fk_tipo = "fk_tipo" . $i;
            $$fk_tipo = "fk_tipo" . $i;

            $usuario_cadastro_id = 1; //DIME

            // Verifica se o arquivo veio tratado como string ou como binário
            if (gettype($p->arquivo0) != "string") {
                //recebe o caminho real do arquivo
                $path = $p->file('arquivo' . $i)->getRealPath();
                $arquivo = file_get_contents($path);
                $usuario_cadastro_id = 26; // TROCAR PELA AUTENTICAÇÃO!!!
            } else {
                $arquivo = $p->$$arquivo;
            }

            // Salva um novo ArquivoAvaliacaoServidor no banco
            $av_arquivo = new ArquivoAvaliacaoServidor();
            $av_arquivo->arquivo = DB::raw("decode('" . base64_encode($arquivo) . "', 'base64')");
            $av_arquivo->descricao = $p->$$descricao;
            $av_arquivo->nome_arquivo = $p->$$nome;
            $av_arquivo->fk_processo_avaliacao = $p->processo_avaliacao_id;
            $av_arquivo->fk_servidor = $p->servidor_id;
            $av_arquivo->fk_usuario_cad = $usuario_cadastro_id;
            $av_arquivo->fk_tipo_arquivo = $p->$$fk_tipo;

            $av_arquivo->save();
        }
        // Muda o status para pendencia após anexar um documento do tipo ficha de avaliação (fk_tipo=2)
            $processo_avaliacao_servidor = ProcessoAvaliacaoServidor::where('fk_processo_avaliacao', '=', $p->processo_avaliacao_id)
            ->where('fk_servidor', '=', $p->servidor_id)
            ->first();
            if ($processo_avaliacao_servidor) {
                if ($p->$$fk_tipo == 2) {
                    $processo_avaliacao_servidor->status = 3;
                } elseif ($p->$$fk_tipo == 5) {
                    $processo_avaliacao_servidor->status = 4;   
                }
                $processo_avaliacao_servidor->save();
            }
    }

    // Exclui >>>!!PERMANENTEMENTE!!<<< um arquivo
    public static function excluirArquivo(ArquivoAvaliacaoServidor $arquivo)
    {
        $arquivo->forceDelete();
        $processo_avaliacao_servidor = ProcessoAvaliacaoServidor::where('fk_processo_avaliacao', '=', $arquivo->fk_processo_avaliacao)
        ->where('fk_servidor', '=', $arquivo->fk_servidor)
        ->first();
        if ($arquivo->fk_tipo_arquivo == 2 || $arquivo->fk_tipo_arquivo == 5) {
            $processo_avaliacao_servidor->status = 1;
            $processo_avaliacao_servidor->save();
        }
    }

    // Baixa um arquivo específico
    public static function exibirArquivo(Request $request)
    {
        //dd($request);
        $arquivo = ArquivoAvaliacaoServidor::find($request->id);
        $arquivo_resposta = stream_get_contents($arquivo->arquivo, -1);


        return response($arquivo_resposta, 200, [
            'Content-Disposition' => 'attachment; filename="myfile.pdf"',
            'Content-Type' => mime_content_type($arquivo->arquivo),
        ]);

    }
}
