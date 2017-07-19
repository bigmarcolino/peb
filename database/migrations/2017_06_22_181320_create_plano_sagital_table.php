<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanoSagitalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plano_sagital', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('valor')->nullable();
            $table->integer('diferenca')->nullable();
            $table->string('localizacao')->nullable();
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
        Schema::dropIfExists('plano_sagital');
    }
}
