<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalEscolioseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_escoliose', function (Blueprint $table) {
            $table->increments('id');
            $table->string('local')->nullable();
            $table->string('lado')->nullable();
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
        Schema::dropIfExists('local_escoliose');
    }
}
