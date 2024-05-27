<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioCadastroAvaliador extends Model
{
    use HasFactory;

    protected $table = "usuario_cadastro_avaliador";
    protected $guarded = [];
}
