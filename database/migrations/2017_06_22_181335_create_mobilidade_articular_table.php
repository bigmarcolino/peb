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
            $table->float('valor')->nullable();
            $table->string('inclinacao')->nullable();
            $table->string('lado')->nullable();
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
