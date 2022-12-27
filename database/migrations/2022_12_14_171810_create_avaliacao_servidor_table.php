<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvaliacaoServidorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliacao_servidor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fk_processo_avaliacao')->constrained('processo_avaliacao');
            $table->foreignId('fk_servidor')->constrained('srh.sig_servidor', 'id_servidor');
            $table->foreignId('fk_fator_avaliacao_item')->constrained('fator_avaliacao_item');
            $table->integer('nota');
            $table->unique(['fk_processo_avaliacao', 'fk_servidor', 'fk_fator_avaliacao_item']);
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
        Schema::dropIfExists('avaliacao_servidor');
    }
}
