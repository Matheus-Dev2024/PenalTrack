<?php

namespace App\Http\Controllers;

use App\Models\Entity\FatorAvaliacao;
use App\Models\Entity\ProcessoAvaliacao;
use App\Models\Facade\AvaliacaoDB;
use App\Models\Facade\FatorAvaliacaoDB;
use App\Models\Facade\ProcessoAvaliacaoDB;
use App\Models\Facade\ServidorDB;
use App\Models\Regras\AvaliacaoServidorRegras;
use App\Models\Regras\ProcessoAvaliacaoRegras;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AvaliacaoController extends Controller
{
    public function formulario()
    {
        $formulario = FatorAvaliacao::all();

        foreach($formulario as $fator) {
            $fator->itens;
        }

        return response()->json($formulario);
    }

    public function info(Request $request)
    {
        $p = (object) $request->validate([
            'processo_id' => 'required',
            'servidor_id' => 'required'
        ]);
        return AvaliacaoServidorRegras::info($p);
    }

    public function store(Request $request)
    {
        //Inicia o Database Transaction
        DB::beginTransaction();
        try {
            $params = (object) $request->all();
            AvaliacaoServidorRegras::adicionarNotas($params);
            ProcessoAvaliacaoRegras::atualizaSituacaoServidor($params);
            DB::commit();
            return response()->json(['message' => 'Avaliação enviada com sucesso.']);
        } catch(Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao tentar enviar a Avaliação do servidor. '.$ex->getMessage()], 500);
        }
    }

    public function getServidoresAvaliacaoCorrente()
    {
        //$usuario_id = Auth::user()->id;
        $usuario_id = 587;


        $servidoresGrupo1 = DB::table("usuario_avalia_servidores as uas")
            ->join("srh.sig_servidor as s", "s.id_servidor", "=", "uas.servidor_id")
            ->join("processo_avaliacao_servidor as pas", "pas.fk_servidor", "=", "s.id_servidor")
            ->join("processo_situacao_servidor as pss", "pss.id", "=", "pas.status")
            ->join("srh.sig_cargo as c", "c.id", "=", "s.fk_id_cargo")
            ->join("policia.unidade as u", "u.id", "=", "pas.fk_unidade")
            ->where('uas.usuario_id', $usuario_id)
            ->select([
                'pas.fk_processo_avaliacao',
                's.id_servidor as servidor_id',
                's.matricula',
                's.nome',
                'u.id as unidade_id',
                'u.nome as unidade',
                'c.abreviacao as cargo',
                'pss.nome as situacao',
                'pas.status'
            ])
            ->distinct()
            ->get();


        $servidoresGrupo2 = DB::table("usuario_avalia_unidades as uau")
        // ->join("policia.unidade as u", "u.id", "=", "uau.unidade_id")
            ->join("processo_avaliacao_servidor as pas", "pas.fk_unidade", "=", "uau.unidade_id")
            ->join("policia.unidade as u", "u.id", "=", "pas.fk_unidade")
            ->join("srh.sig_servidor as s", "s.id_servidor", "=", "pas.fk_servidor")
            ->join("processo_situacao_servidor as pss", "pss.id", "=", "pas.status")
            ->join("srh.sig_cargo as c", "c.id", "=", "s.fk_id_cargo")
            ->where('uau.usuario_id', $usuario_id)
            ->select([
                'pas.fk_processo_avaliacao as processo_avaliacao_id',
                's.id_servidor as servidor_id',
                's.matricula',
                's.nome',
                'u.id as unidade_id',
                'u.nome as unidade',
                'c.abreviacao as cargo',
                'pss.nome as situacao',
                'pas.status'
            ])
            ->distinct()
            ->get();


        $servidores = $servidoresGrupo1->merge($servidoresGrupo2)->sortBy('nome');


        return response()->json($servidores);
    }






    // Retorna os arquivos de uma Avaliação
    public function GridArquivos(Request $request){
        $p = (object) $request->validate([
            'servidor_id' => 'required',
            'processo_avaliacao_id' => 'required'
        ]);
        return response()->json(AvaliacaoServidorRegras::gridArquivos($p));
    }

    // Faz o upload de um arquivo para o banco
    public function uploadArquivo(Request $request){
        $p = (object)$request->all();
        DB::beginTransaction();
        try{
            AvaliacaoServidorRegras::uploadArquivo($request);
            DB::commit();
            return response()->json([
                'message' => 'Arquivo armazenado com sucesso'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage()], 500);
            } else {
                return response()->json(['message' => 'Falha ao gravar arquivos'], 500);
            }
        }
    }

}
