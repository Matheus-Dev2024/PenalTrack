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
        Schema::create('seg_grupo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('seguranca.usuario');
            $table->foreignId('perfil_id')->constrained('seg_perfil');

            $table->unique(['usuario_id', 'perfil_id']);

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
        Schema::drop('seg_grupo');
    }
};
