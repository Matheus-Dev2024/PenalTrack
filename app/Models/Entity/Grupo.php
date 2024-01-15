<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    //timestemp = false pois na tabela grupo não existe a colunda updated_at
    public $timestamps = false;
    //incrementing = false pois na tabela grupo não existe a coluna id
    public $incrementing = false;
    protected $table = "srh.grupo";
    protected $guarded = [];
}
