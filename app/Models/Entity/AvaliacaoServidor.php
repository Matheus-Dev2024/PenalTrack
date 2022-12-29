<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvaliacaoServidor extends Model
{
    use HasFactory;
    protected $table = "avaliacao_servidor";
    protected $guarded = [];
}
