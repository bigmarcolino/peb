<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurvaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curva', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ordenacao')->nullable();
            $table->string('tipo')->nullable();
            $table->integer('angulo_cobb')->nullable();
            $table->integer('angulo_ferguson')->nullable();
            $table->integer('grau_rotacao')->nullable();
            $table->integer('diagnostico_prognostico_id')->unsigned()->nullable();
            $table->foreign('diagnostico_prognostico_id')->references('id')->on('diagnostico_prognostico')->onDelete('cascade');
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
        Schema::dropIfExists('curva');
    }
}
