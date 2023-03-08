<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property string nome
 **/

class TipoArquivo extends Model
{
    use HasFactory;
    protected $table = "tipo_arquivo";
    protected $guarded = [];
}
