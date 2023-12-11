<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServidorComissao extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "servidor_comissao";
    protected $fillable = [
        'fk_servidor', 
    ];

}
