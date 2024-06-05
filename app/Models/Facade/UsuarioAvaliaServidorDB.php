<?php

namespace App\Models\Facade;

use Illuminate\Support\Facades\DB;
use App\Models\Entity\UsuarioAvaliaServidores;
use Illuminate\Support\Collection;
use stdClass;

class UsuarioAvaliaServidorDB
{
    public static function grid(\stdClass $p) :Collection
    {
        //$srh = config('database.connections.conexao_srh.schema');
        $select = [
            'uas.id',
            'uas.usuario_id',
            'uas.fk_processo_avaliacao_servidor',
            'ss.nome'
        ];
        
        $sql = DB::table('usuario_avalia_servidores as uas')
        ->join('processo_avaliacao_servidor as pas', 'pas.id', '=', 'uas.fk_processo_avaliacao_servidor')
        ->join("srh.sig_servidor as ss", 'ss.id_servidor', '=', 'uas.servidor_id')
        ->where('uas.usuario_id', '=', $p->usuario_id)
        ->orderBy('ss.nome')
        ->select($select); 

        return $sql->get();
    }
}
