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
        Schema::create('seg_acao', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('method')->default('GET');
            $table->string('descricao')->nullable();
            $table->boolean('destaque')->default(false);
            $table->string('nome_amigavel')->nullable();
            $table->boolean('obrigatorio')->default(false);
            $table->string('grupo')->nullable();
            $table->boolean('log_acesso')->default(false);
            $table->boolean('rota_front')->default(false);
            $table->timestamps();

            $table->index(['nome', 'method']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('seg_acao');
    }
};
