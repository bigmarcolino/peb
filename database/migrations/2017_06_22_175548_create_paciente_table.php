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
            $table->increments('id');
            $table->string('cpf')->unique()->nullable();
            $table->string('nome');
            $table->string('end_res')->nullable();
            $table->date('data_nasc');
            $table->string('estado')->nullable();
            $table->string('cidade')->nullable();
            $table->string('cep')->nullable();
            $table->string('tel_res')->nullable();
            $table->string('tel_trab')->nullable();
            $table->string('medico')->nullable();
            $table->string('celular')->nullable();
            $table->string('indicacao')->nullable();
            $table->string('identidade')->nullable();
            $table->string('email')->nullable();
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
