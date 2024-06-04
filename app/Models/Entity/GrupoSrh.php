<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoSrh extends Model
{
    use HasFactory;    
    protected $table = "srh.grupo";
    protected $guarded = [];
}