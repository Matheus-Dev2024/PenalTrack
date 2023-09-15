<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodoProcessoAvaliacao extends Model
{
    use HasFactory;

    protected $table = "periodos_processo";

    public function processo()
    {
        return $this->hasMany(ProcessoAvaliacao::class, 'fk_periodo_processo', 'id');
    }
}
