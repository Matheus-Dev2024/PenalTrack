<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioSistema extends Model
{
    use HasFactory;

    protected $table = "seguranca.usuario_sistema";
    public $timestamps = false;
    protected $guarded = [];

}
