<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVertebraApiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vertebra_apice', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipo_escoliose')->nullable();
            $table->string('vertebra_nome')->nullable();
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
        Schema::dropIfExists('vertebra_apice');
    }
}
