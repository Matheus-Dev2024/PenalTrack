<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anexos extends Model
{
   protected $table = 'penaltrack.anexos';

   protected $fillable = ['arquivos_uuid', 'presos_id'];
}
