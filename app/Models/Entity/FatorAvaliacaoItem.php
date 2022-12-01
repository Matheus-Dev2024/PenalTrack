<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FatorAvaliacaoItem extends Model
{
    use HasFactory;
    protected $table = "fator_avaliacao_item";
    protected $guarded = [];
}
