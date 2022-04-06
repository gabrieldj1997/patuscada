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
            $table->string('nome_jogo');
            $table->string('id_estado_jogo')->default(0);
            $table->integer('rodada')->default(0);
            $table->integer('user_master')->nullable();
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
        Schema::dropIfExists('jogos');
    }
};
