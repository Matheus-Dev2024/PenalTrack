<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioAvaliadorIntermediario extends Model
{
    use HasFactory;

    protected $table = "usuario_avaliador_intermediario";
    protected $guarded = [];
}
