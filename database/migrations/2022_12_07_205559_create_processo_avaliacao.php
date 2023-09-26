<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessoAvaliacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processo_avaliacao', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->date('dt_inicio_avaliacao');
            $table->date('dt_termino_avaliacao');
            $table->date('dt_inicio_estagio');
            $table->date('ref_inicio');
            $table->date('ref_termino');
            $table->string('instrucao', 800);
            $table->foreignId('fk_periodo_processo')->constrained('periodos_processo');
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
        Schema::dropIfExists('processo_avaliacao');
    }
}
