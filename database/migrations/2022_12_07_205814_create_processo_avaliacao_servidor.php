<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessoAvaliacaoServidor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processo_avaliacao_servidor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fk_processo_avaliacao')->constrained('processo_avaliacao');
            $table->foreignId('fk_servidor')->constrained('srh.sig_servidor', 'id_servidor');
            $table->foreignId('fk_unidade')->constrained('policia.unidade');
            $table->foreignId('fk_avaliador')->constrained('seguranca.usuario');
            $table->integer('dias_estagio');
            $table->integer('dias_trabalho_programado');
            $table->integer('dias_ausencia');
            $table->integer('dias_trabalhados');
            $table->integer('dias_prorrogados');
            $table->float('nota_total');
            // $table->foreignId('status')->constrained('processo_situacao_servidor');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('processo_avaliacao_servidor');
    }
}
