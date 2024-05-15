<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessoAcompanhamentoAnexos extends Model
{
    //model criado para uma tabela unica de anexos entre dif e comissao "processo_acompanhamento_anexos"

    use HasFactory;
    protected $table = 'processo_acompanhamento_anexos';

}
