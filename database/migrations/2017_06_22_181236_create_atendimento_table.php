<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtendimentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atendimento', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idade_cronologica')->nullable();
            $table->integer('idade_ossea')->nullable();
            $table->date('menarca')->nullable();
            $table->float('altura')->nullable();
            $table->float('altura_sentada')->nullable();
            $table->float('peso')->nullable();
            $table->integer('risser')->nullable();
            $table->date('data_raio_x')->nullable();
            $table->string('cpf_paciente')->nullable();
            $table->foreign('cpf_paciente')->references('cpf')->on('paciente')->onDelete('cascade');
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
        Schema::dropIfExists('atendimento');
    }
}
