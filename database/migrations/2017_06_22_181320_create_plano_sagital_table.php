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
            $table->integer('valor_cabeca')->nullable();
            $table->integer('valor_cervical')->nullable();
            $table->integer('valor_c7')->nullable();
            $table->integer('valor_t5_t6')->nullable();
            $table->integer('valor_t12')->nullable();
            $table->integer('valor_l3')->nullable();
            $table->integer('valor_s1')->nullable();
            $table->integer('compensacao_cabeca')->nullable();
            $table->integer('compensacao_cervical')->nullable();
            $table->integer('compensacao_c7')->nullable();
            $table->integer('compensacao_t5_t6')->nullable();
            $table->integer('compensacao_t12')->nullable();
            $table->integer('compensacao_l3')->nullable();
            $table->integer('compensacao_s1')->nullable();
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
