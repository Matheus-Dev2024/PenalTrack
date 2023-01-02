<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArquivoAvaliacaoServidorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arquivo_avaliacao_servidor', function (Blueprint $table) {
            $table->id();
            $table->binary('arquivo')->comment('O arquivo de Upload');
            $table->string('nome_arquivo', 255);
            $table->string('descricao', 255);
            $table->foreignId('fk_processo_avaliacao')->comment('O Processo de avaliacao ao qual esse anexo pertense')->constrained('processo_avaliacao');
            $table->foreignId('fk_servidor')->comment('O Servidor ao qual este aquivo pertence')->constrained('srh.sig_servidor', 'id_servidor');
            $table->foreignId('fk_usuario_cad')->nullable()->constrained('seguranca.usuario');
            $table->foreignId('fk_usuario_exc')->nullable()->constrained('seguranca.usuario');
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
        Schema::dropIfExists('arquivo_avaliacao_servidor');
    }
}
