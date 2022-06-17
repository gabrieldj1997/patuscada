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
        Schema::create('tb_jogos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->integer('id_jogador_criador');
            $table->string('jogadores')->nullable();
            $table->string('cartas_brancas_monte');
            $table->string('cartas_pretas_monte');
            $table->string('cartas_pretas_jogo');
            $table->integer('rodada_jogo')->default(0);
            $table->integer('estado_jogo')->default(0);
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
        Schema::dropIfExists('tb_jogos');
    }
};
