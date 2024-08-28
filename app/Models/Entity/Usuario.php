<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = "seguranca.usuario";

    public $timestamps = false;
    protected $guarded = [];

    public function setCpfAttribute($value)
    {
        $this->attributes['cpf'] = str_replace(['.', '-'], '', $value);
    }
}
