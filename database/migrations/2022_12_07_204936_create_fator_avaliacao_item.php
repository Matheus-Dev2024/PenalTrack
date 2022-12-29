<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFatorAvaliacaoItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fator_avaliacao_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fk_fator_avaliacao')->constrained('fator_avaliacao');
            $table->string('pergunta', 500);
            $table->integer('status');
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
        Schema::dropIfExists('fator_avaliacao_item');
    }
}
