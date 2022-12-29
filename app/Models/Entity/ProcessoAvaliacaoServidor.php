<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessoAvaliacaoServidor extends Model
{
    use HasFactory;
    protected $table = "processo_avaliacao_servidor";
    protected $guarded = [];
    
}
