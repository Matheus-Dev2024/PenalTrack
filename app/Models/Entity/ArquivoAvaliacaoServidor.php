<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArquivoAvaliacaoServidor extends Model
{
    use HasFactory;
    protected $table = "arquivo_avaliacao_servidor";
    protected $guarded = [];
}
