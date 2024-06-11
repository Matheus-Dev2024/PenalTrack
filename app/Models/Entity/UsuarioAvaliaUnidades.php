<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioAvaliaUnidades extends Model
{
    use HasFactory;
    protected $table = "usuario_avalia_unidades";
    protected $guarded = [];

    public function scopeUnidadesParaServidorAvaliar($query, $usuario_id) {
        return $query->where('usuario_id', $usuario_id)->select('unidade_id');
    }
}
