<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponsavelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsavel', function (Blueprint $table) {
            $table->string('cpf')->unique();
            $table->string('nome')->nullable();
            $table->string('email')->nullable();
            $table->string('identidade')->nullable();
            $table->string('ocupacao')->nullable();
            $table->string('telefone')->nullable();
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
        Schema::dropIfExists('responsavel');
    }
}
