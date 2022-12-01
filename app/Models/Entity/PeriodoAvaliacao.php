<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessoAvaliacao extends Model
{
    use HasFactory;
    protected $table = "processo_avaliacao";
    protected $guarded = [];
    
}
