<?php

namespace App\Models\Facade;

use Illuminate\Support\Facades\DB;
use App\Models\Entity\UsuarioAvaliaServidores;
use stdClass;

class UsuarioAvaliaServidorDB
{
    public static function grid(\stdClass $p)
    {
        $srh = config('database.connections.conexao_srh.schema');
        $select = [
            'uas.id',
            'uas.usuario_id',
            'uas.fk_processo_avaliacao',
            'pa.descricao',
            'ss.nome'
        ];

        $sql = DB::table('usuario_avalia_servidores as uas')
        ->join('processo_avaliacao as pa', 'pa.id', '=', 'uas.fk_processo_avaliacao')
        ->join("$srh.sig_servidor as ss", 'ss.id_servidor', '=', 'uas.servidor_id')
        ->where('uas.usuario_id', '=', $p->usuario_id)
        ->orderBy('ss.nome')
        ->select($select);

        // if(isset($p->usuario_id)){
        //    $sql->where('uas.usuario_id', '=', $p->usuario_id);
        // }
        // if(isset($p->processo_avaliacao)){
        //     $sql->where('uas.fk_processo_avaliacao', '=', $p->processo_avaliacao);
        // }

        return $sql->get();
    }
}
