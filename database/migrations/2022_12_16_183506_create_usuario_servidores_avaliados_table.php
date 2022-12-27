<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuarioServidoresAvaliadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_avalia_servidores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('seguranca.usuario');
            $table->foreignId('servidor_id')->constrained('srh.sig_servidor', 'id_servidor');
            $table->unique(['usuario_id', 'servidor_id']);
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
        Schema::dropIfExists('usuario_avalia_servidores');
    }
}
