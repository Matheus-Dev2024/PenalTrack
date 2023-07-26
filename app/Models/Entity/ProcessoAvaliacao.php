<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessoAvaliacao extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "processo_avaliacao";
    protected $guarded = [];
    
}
