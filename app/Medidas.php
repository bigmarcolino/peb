<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medidas extends Model
{
	protected $table = 'medidas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assimetria_ombro', 'assimetria_escapulas', 'hemi_torax', 'cintura', 'teste_fukuda_deslocamento_direito', 'teste_fukuda_deslocamento_esquerdo', 'teste_fukuda_rotacao_direito', 'teste_fukuda_rotacao_esquerdo', 'teste_fukuda_desvio_direito', 'teste_fukuda_desvio_esquerdo', 'habilidade_ocular_direito', 'habilidade_ocular_esquerdo', 'romberg_mono_direito', 'romberg_mono_esquerdo', 'romberg_mono_observacao', 'romberg_sensibilizado_direito', 'romberg_sensibilizado_esquerdo', 'romberg_sensibilizado_observacao', 'balanco_direito', 'balanco_esquerdo', 'balanco_observacao', 'retracao_posterior', 'retracao_posterior_observacao', 'teste_thomas_direito', 'teste_thomas_esquerdo', 'teste_thomas_observacao', 'retracao_peitoral_direito', 'retracao_peitoral_esquerdo', 'retracao_peitoral_observacao', 'forca_muscular_abs', 'forca_muscular_observacao', 'forca_ext_tronco', 'forca_ext_tronco_observacao', 'resistencia_extensores_tronco', 'resistencia_extensores_tronco_observacao'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'id', 'atendimento_id'
    ];

    public function plano_frontal()
    {
        return $this->hasOne('App\PlanoFrontal');
    }
    
    public function plano_horizontal_milimetros()
    {
        return $this->hasOne('App\PlanoHorizontalMilimetros');
    }

    public function plano_horizontal_graus()
    {
        return $this->hasOne('App\PlanoHorizontalGraus');
    }
    
    public function plano_sagital()
    {
        return $this->hasOne('App\PlanoSagital');
    }

    public function mobilidade_articular()
    {
        return $this->hasOne('App\MobilidadeArticular');
    }
}
