<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paciente', function (Blueprint $table) {
            $table->string('cpf')->unique();
            $table->string('nome');
            $table->string('end_res');
            $table->date('data_nasc');
            $table->string('estado');
            $table->string('cidade');
            $table->string('cep');
            $table->string('tel_res');
            $table->string('tel_trab');
            $table->string('medico');
            $table->string('celular');
            $table->string('indicacao');
            $table->string('identidade');
            $table->string('email');
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
        Schema::dropIfExists('paciente');
    }
}
