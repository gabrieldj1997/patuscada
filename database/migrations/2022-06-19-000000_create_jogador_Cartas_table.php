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
        Schema::create('tb_jogador_cartas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_jogo');
            $table->integer('id_jogador');
            $table->string('pontuacao');
            $table->string('cartas');
            $table->string('updated_at');
            $table->string('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_jogador_cartas');
    }
};
