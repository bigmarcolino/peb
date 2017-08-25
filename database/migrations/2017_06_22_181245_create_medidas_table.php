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
            $table->string('assimetria_ombro')->nullable();
            $table->string('assimetria_escapulas')->nullable();
            $table->string('hemi_torax')->nullable();
            $table->string('cintura')->nullable();
            $table->integer('teste_fukuda_deslocamento_direito')->nullable();
            $table->integer('teste_fukuda_deslocamento_esquerdo')->nullable();
            $table->integer('teste_fukuda_rotacao_direito')->nullable();
            $table->integer('teste_fukuda_rotacao_esquerdo')->nullable();
            $table->integer('teste_fukuda_desvio_direito')->nullable();
            $table->integer('teste_fukuda_desvio_esquerdo')->nullable();
            $table->string('habilidade_ocular_direito')->nullable();
            $table->string('habilidade_ocular_esquerdo')->nullable();
            $table->integer('romberg_mono_direito')->nullable();
            $table->integer('romberg_mono_esquerdo')->nullable();
            $table->string('romberg_mono_observacao')->nullable();
            $table->integer('romberg_sensibilizado_direito')->nullable();
            $table->integer('romberg_sensibilizado_esquerdo')->nullable();
            $table->string('romberg_sensibilizado_observacao')->nullable();
            $table->string('balanco_direito')->nullable();
            $table->string('balanco_esquerdo')->nullable();
            $table->string('balanco_observacao')->nullable();
            $table->integer('retracao_posterior')->nullable();
            $table->string('retracao_posterior_observacao')->nullable();
            $table->integer('teste_thomas_direito')->nullable();
            $table->integer('teste_thomas_esquerdo')->nullable();
            $table->string('teste_thomas_observacao')->nullable();
            $table->integer('retracao_peitoral_direito')->nullable();
            $table->integer('retracao_peitoral_esquerdo')->nullable();
            $table->string('retracao_peitoral_observacao')->nullable();
            $table->integer('forca_muscular_abs')->nullable();
            $table->string('forca_muscular_observacao')->nullable();
            $table->integer('forca_ext_tronco')->nullable();
            $table->string('forca_ext_tronco_observacao')->nullable();
            $table->integer('resistencia_extensores_tronco')->nullable();
            $table->string('resistencia_extensores_tronco_observacao')->nullable();
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
