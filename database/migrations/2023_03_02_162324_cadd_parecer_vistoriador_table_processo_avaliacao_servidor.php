<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CaddParecerVistoriadorTableProcessoAvaliacaoServidor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processo_avaliacao_servidor', function (Blueprint $table) {
            $table->string('parecer_avaliador', 2048)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('processo_avaliacao_servidor', function (Blueprint $table) {
            $table->dropColumn('parecer_avaliador');
        });
    }
}
