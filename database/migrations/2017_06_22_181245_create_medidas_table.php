<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medidas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assimetria_ombro')->nullable();
            $table->integer('assimetria_escapulas')->nullable();
            $table->integer('hemi_torax')->nullable();
            $table->integer('cintura')->nullable();
            $table->integer('teste_fukuda_deslocamento')->nullable();
            $table->integer('teste_fukuda_rotacao')->nullable();
            $table->integer('teste_fukuda_desvio')->nullable();
            $table->string('habilidade_ocular_direito')->nullable();
            $table->string('habilidade_ocular_esquerdo')->nullable();
            $table->integer('romberg_mono_direito')->nullable();
            $table->integer('romberg_mono_esquerdo')->nullable();
            $table->integer('romberg_sensibilizado_direito')->nullable();
            $table->integer('romberg_sensibilizado_esquerdo')->nullable();
            $table->string('balanco_direito')->nullable();
            $table->string('balanco_esquerdo')->nullable();
            $table->integer('retracao_posterior')->nullable();
            $table->integer('teste_thomas_direito')->nullable();
            $table->integer('teste_thomas_esquerdo')->nullable();
            $table->integer('retracao_peitoral_direito')->nullable();
            $table->integer('retracao_peitoral_esquerdo')->nullable();
            $table->integer('forca_muscular_abs')->nullable();
            $table->integer('forca_ext_tronco')->nullable();
            $table->integer('resistencia_extensores_tronco')->nullable();
            $table->integer('atendimento_id')->nullable()->unsigned();
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
        Schema::dropIfExists('medidas');
    }
}
