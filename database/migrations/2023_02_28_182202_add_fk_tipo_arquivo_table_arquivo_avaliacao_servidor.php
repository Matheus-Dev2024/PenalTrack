<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkTipoArquivoTableArquivoAvaliacaoServidor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('arquivo_avaliacao_servidor', function (Blueprint $table) {
            $table->foreignId('fk_tipo_arquivo')->constrained('tipo_arquivo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('arquivo_avaliacao_servidor', function (Blueprint $table) {
            //
        });
    }
}
