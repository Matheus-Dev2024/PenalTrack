<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioAvaliaServidores extends Model
{
    use HasFactory;
    protected $table = "usuario_avalia_servidores";
    protected $guarded = [];
}
