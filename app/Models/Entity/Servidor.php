<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servidor extends Model
{
    protected $table = "srh.sig_servidor";
    protected $primaryKey = 'id_servidor';
}
