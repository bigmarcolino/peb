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
            $table->string('tipo')->nullable();
            $table->string('local_escoliose')->nullable();
            $table->string('cifose')->nullable();
            $table->string('lordose')->nullable();
            $table->string('prescricao_medica')->nullable();
            $table->string('prescricao_fisioterapeutica')->nullable();
            $table->string('colete')->nullable();
            $table->integer('colete_hs')->nullable();
            $table->string('etiologia')->nullable();
            $table->integer('idade_aparecimento')->nullable();
            $table->string('topografia')->nullable();
            $table->string('calco_utilizado_direito')->nullable();
            $table->string('calco_utilizado_esquerdo')->nullable();
            $table->integer('tamanho_calco_direito')->nullable();
            $table->integer('tamanho_calco_esquerdo')->nullable();
            $table->text('hpp')->nullable();
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
