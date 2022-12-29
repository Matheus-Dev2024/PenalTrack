<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_dependencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acao_atual_id')->constrained('seg_acao');
            $table->foreignId('acao_dependencia_id')->constrained('seg_acao');
            $table->timestamps();

//            $table->foreign('acao_atual_id')->references('id')->on('seg_acao');
//            $table->foreign('acao_dependencia_id')->references('id')->on('seg_acao');

            $table->unique(['acao_atual_id', 'acao_dependencia_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('seg_dependencia');
    }
};
