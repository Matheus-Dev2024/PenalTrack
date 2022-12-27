<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuarioUnidadesAvaliadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_avalia_unidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('seguranca.usuario');
            $table->integer('unidade_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_avalia_unidades');
    }
}
