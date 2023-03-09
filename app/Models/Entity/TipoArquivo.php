<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string nome
 **/

class TipoArquivo extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "tipo_arquivo";
    protected $guarded = [];
}
