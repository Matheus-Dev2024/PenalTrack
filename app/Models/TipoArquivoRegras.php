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
    public static function salvar (\stdClass $p): TipoArquivo
    {
        $tipo = new TipoArquivo;
        $tipo->nome = $p->nome;
        $tipo->save();

        return $tipo;
    }
    public static function alterar (\stdClass $p): void
    {
        $tipo = TipoArquivo::find($p->id);
        $tipo->nome = $p->nome;
        $tipo->save();
    }
}
