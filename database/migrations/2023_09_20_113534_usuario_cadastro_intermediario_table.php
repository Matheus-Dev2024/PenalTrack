<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsuarioCadastroIntermediarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_avaliador_intermediario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_cadastrado')->constrained('seguranca.usuario');
            $table->foreignId('usuario_cadastrou')->constrained('seguranca.usuario');
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
        Schema::dropIfExists('usuario_avaliador_intermediario');
    }
}
