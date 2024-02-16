<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comissao extends Model
{
    use HasFactory;
    protected $table = "comissao";

    protected $guarded = [];
}
