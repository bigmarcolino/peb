<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFotoPacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foto_paciente', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome_foto')->nullable();
            $table->date('data_foto')->nullable();
            $table->binary('foto');
            $table->string('descricao')->nullable();
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
        Schema::dropIfExists('foto_paciente');
    }
}
