<?php

namespace App\Models;

use App\Models\Entity\TipoArquivo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoArquivoRegras extends Model
{
    public static function grid ()
    {
        return TipoArquivo::all('id as value', 'nome as text');
    }
}
