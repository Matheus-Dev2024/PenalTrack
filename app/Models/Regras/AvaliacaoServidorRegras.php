<?php

namespace App\Models\Regras;

use App\Models\Entity\ArquivoAvaliacaoServidor;
use App\Models\Entity\AvaliacaoServidor;
use App\Models\Entity\PessoaJuridicaArquivo;
use App\Models\Facade\AvaliacaoDB;
use App\Models\Facade\FatorAvaliacaoDB;
use App\Models\Facade\ProcessoAvaliacaoDB;
use App\Models\Facade\ServidorDB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AvaliacaoServidorRegras
{
    public static function info (\stdClass $p){
        $formulario = FatorAvaliacaoDB::getFormularioAvaliacao();
        $processo = ProcessoAvaliacaoDB::getById($p->processo_id);
        $servidor = ServidorDB::info($p->servidor_id, $processo->processo_id, $processo->dt_inicio_avaliacao_en, $processo->dt_termino_avaliacao_en);
        $ausencias = ServidorDB::listaAusenciasPorPeriodo($p->servidor_id, $processo->dt_inicio_avaliacao_en, $processo->dt_termino_avaliacao_en);

        $arquivo_avaliacao = ArquivoAvaliacaoServidor::where('fk_servidor', $p->servidor_id)
        ->where('fk_processo_avaliacao', $p->processo_id)
        ->first([
            'id'
        ]);

        $notas = AvaliacaoDB::getNotasServidor($processo->processo_id, $p->servidor_id);
        $impressao = false;
        if(isset ($notas[0])) $impressao = true;

        return response()->json([
            'processo' => $processo,
            'formulario' => $formulario,
            'notas' => $notas,
            'servidor' => $servidor,
            'ausencias' => $ausencias,
            'habilitarimpressao' =>$impressao,
            'arquivo_avaliacao' => $arquivo_avaliacao
        ]);
    }

    public static function adicionarNotas($p)
    {

        AvaliacaoServidor::where('fk_processo_avaliacao', $p->processo_avaliacao_id)
                        ->where('fk_servidor', $p->servidor_id)
                        ->delete();

        foreach($p->notas as $item_id => $nota) {

            if(!empty($nota)) {

                AvaliacaoServidor::create([
                    'fk_processo_avaliacao'   => $p->processo_avaliacao_id,
                    'fk_servidor'             => $p->servidor_id,
                    'fk_fator_avaliacao_item' => $item_id,
                    'nota'                    => $nota
                ]);

            }
        }
    }


    public static function gridArquivos(\stdClass $p){
        return AvaliacaoDB::gridArquivos($p);
    }
    public static function uploadArquivo($p){

        //procura o arquivo para excluí_lo
        $arquivo_velho = ArquivoAvaliacaoServidor::where('fk_processo_avaliacao', $p->processo_avaliacao_id)
            ->where('fk_servidor', $p->servidor_id)
            ->first();

        if($arquivo_velho != null)
        $arquivo_velho->forceDelete();

        for ($i = 0; $i < $p->quantidade; $i++){

            // Cria e instancia as variáveis dinâmicas
            $arquivo = "arquivo".$i;
            $$arquivo = "arquivo".$i;
            $descricao = "descricao".$i;
            $$descricao = "descricao".$i;
            $nome = "nome".$i;
            $$nome = "nome".$i;

            $usuario_cadastro_id = 1; //DIME

            // Verifica se o arquivo veio tratado como string ou como binário
            if(gettype($p->arquivo0) != "string"){
                //recebe o caminho real do arquivo
                $path = $p->file('arquivo'.$i)->getRealPath();
                $arquivo = file_get_contents($path);
                $usuario_cadastro_id = 26; // TROCAR PELA AUTENTICAÇÃO!!!
            }else{
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

            $av_arquivo->save();
        }
    }

    // Exclui >>>!!PERMANENTEMENTE!!<<< um arquivo
    public static function excluirArquivo(ArquivoAvaliacaoServidor $arquivo){
        $arquivo->forceDelete();
    }

    // Baixa um arquivo específico
    public static function exibirArquivo(Request $request){
        //dd($request);
        $arquivo = ArquivoAvaliacaoServidor::find($request->id);
        $arquivo_resposta = stream_get_contents($arquivo->arquivo, -1);


        return response($arquivo_resposta, 200, [
            'Content-Disposition' => 'attachment; filename="myfile.pdf"',
            'Content-Type' => mime_content_type($arquivo->arquivo),
        ]);

    }
}
