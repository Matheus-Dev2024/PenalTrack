<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('seguranca.usuario');
            $table->foreignId('acao_id')->constrained('seg_acao');
            $table->json('antes')->nullable();
            $table->json('depois')->nullable();
            $table->string('ip');
            $table->string('url', 2048);//tamanho máximo do get no protocolo http
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
        Schema::drop('seg_historico');
    }
};
