<?php

namespace App\Http\Controllers;

use App\Models\Entity\DocumentacaoEstagioComissao;
use App\Models\Regras\DocumentacaoEstagioComissaoRegras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentacaoEstagioComissaoController extends Controller
{
    public function store(Request $request) 
    {
        $request->all();        
        DB::beginTransaction();
        try {
            DocumentacaoEstagioComissaoRegras::store($request);
            DB::commit();
            return response()->json(['message' => 'Documento cadastrado com sucesso!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()]);
            // return response()->json(['message' => 'Erro ao cadastrar o documento:', 'errors' => ['error' => $e->getMessage()]], 422);
        }
    }

    public function deletarDocumentoComissao($id)
    {
        DB::beginTransaction();
        try{
            $documentacao = DocumentacaoEstagioComissao::find($id);
            $documentacao->delete();
            DB::commit();
            return response()->json(['message' => 'Excluído com sucesso']);    
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Erro ao excluir o documento:', 'errors' => ['error' => $e->getMessage()]], 422);
        }
    }
}
