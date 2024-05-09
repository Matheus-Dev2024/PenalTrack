<?php

namespace App\Http\Controllers;

use App\Models\Regras\DocumentacaoEstagioDifRegras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentacaoEstagioDifController extends Controller
{
    public function store(Request $request) 
    {
        $request->all();        
        DB::beginTransaction();
        try {
            DocumentacaoEstagioDifRegras::store($request);
            DB::commit();
            return response()->json(['message' => 'Documento cadastrado com sucesso!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()]);
            // return response()->json(['message' => 'Erro ao cadastrar o documento:', 'errors' => ['error' => $e->getMessage()]], 422);
        }
    }
}
