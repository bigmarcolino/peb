<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobilidadeArticularTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobilidade_articular', function (Blueprint $table) {
            $table->increments('id');
            $table->float('valor_reto_direita')->nullable();
            $table->float('valor_reto_esquerda')->nullable();
            $table->float('valor_inclinado_direita')->nullable();
            $table->float('valor_inclinado_esquerda')->nullable();
            $table->float('diferenca_direita')->nullable();
            $table->float('diferenca_esquerda')->nullable();
            $table->integer('medidas_id')->unsigned()->nullable();
            $table->foreign('medidas_id')->references('id')->on('medidas')->onDelete('cascade');
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
        Schema::dropIfExists('mobilidade_articular');
    }
}
