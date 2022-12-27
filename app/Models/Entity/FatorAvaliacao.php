<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FatorAvaliacao extends Model
{
    use HasFactory;
    protected $table = "fator_avaliacao";
    protected $guarded = [];


    public function itens()
    {
        return $this->hasMany(FatorAvaliacaoItem::class, "fk_fator_avaliacao", "id");
    }
}
