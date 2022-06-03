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
        Schema::create('tb_rodadas', function (Blueprint $table) {
            $table->integer('id_jogo');
            $table->string('rodada');
            $table->integer('id_estado_rodada');
            $table->integer('id_jogador_mestre');
            $table->string('jogadores');
            $table->string('cartas_brancas_jogador');
            $table->string('cartas_brancas_monte');
            $table->string('cartas_brancas_descartardas');
            $table->string('cartas_pretas_jogador');
            $table->string('cartas_pretas_monte');
            $table->string('cartas_pretas_descartardas');
            $table->string('cartas_brancas_selecionadas');
            $table->string('carta_preta_selecionada');
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
        Schema::dropIfExists('tb_rodadas');
    }
};
