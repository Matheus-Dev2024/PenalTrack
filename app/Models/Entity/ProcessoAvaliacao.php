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

    public function periodo()
    {
        return $this->belongsTo(PeriodoProcessoAvaliacao::class);
    }

    public function processoServidor()
    {
        return $this->hasMany(ProcessoAvaliacaoServidor::class);
    }
}
