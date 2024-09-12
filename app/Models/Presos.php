<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presos extends Model
{
   protected $table = 'penaltrack.presos';

   protected $fillable = ['nome', 'presos_id'];

public function anexos(): BelongsTo
{
        return $this->belongsTo(Anexos::class, 'presos_id');
    }
}
