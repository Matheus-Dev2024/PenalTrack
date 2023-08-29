<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServidoresAvaliadosIndividualmente extends Model
{
    use HasFactory;
    protected $table = "usuario_avalia_servidores";
    protected $guarded = [];
}
