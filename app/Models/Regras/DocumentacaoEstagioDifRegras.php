<?php

namespace App\Models\Regras;

use App\Models\Entity\DocumentacaoEstagioDif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentacaoEstagioDifRegras
{
    public static function store(Request $request) 
    {
        $anexo = $request->anexo;
        
        $p_anexo = new DocumentacaoEstagioDif();
            $p_anexo->anexo = DB::raw("decode('" . base64_encode($anexo) . "', 'base64')");
            $p_anexo->descricao = $request->descricao;
            $p_anexo->fk_servidor = $request->fk_servidor;    
            $p_anexo->fk_tipo_documento = $request->fk_tipo_documento;
            $p_anexo->usuario_cadastrou = $request->id_servidor;
            $p_anexo->save();
    }
}
