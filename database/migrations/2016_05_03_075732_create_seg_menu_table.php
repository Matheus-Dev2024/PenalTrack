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
        Schema::create('seg_menu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acao_id')->nullable()->constrained('seg_acao');
            $table->foreignId('pai')->nullable()->constrained('seg_menu');
            $table->string('nome');
            $table->string('dica')->nullable();
            $table->boolean('ativo')->default(true);
            $table->smallInteger('ordem');
            $table->json('configuracoes')->nullable();
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
        Schema::drop('seg_menu');
    }
};
