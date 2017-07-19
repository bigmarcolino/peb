<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiagnosticoPrognosticoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnostico_prognostico', function (Blueprint $table) {
            $table->increments('id');
            $table->string('diagnostico_clinico')->nullable();
            $table->string('tipo_escoliose')->nullable();
            $table->boolean('cifose')->nullable();
            $table->boolean('lordose')->nullable();
            $table->string('prescricao_medica')->nullable();
            $table->string('prescricao_fisioterapeutica')->nullable();
            $table->string('colete')->nullable();
            $table->integer('colete_hs')->nullable();
            $table->string('etiologia')->nullable();
            $table->integer('idade_aparecimento')->nullable();
            $table->string('topografia')->nullable();
            $table->string('calco')->nullable();
            $table->string('hpp')->nullable();
            $table->integer('atendimento_id')->unsigned()->nullable();
            $table->foreign('atendimento_id')->references('id')->on('atendimento')->onDelete('cascade');
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
        Schema::dropIfExists('diagnostico_prognostico');
    }
}
