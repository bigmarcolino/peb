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
        'assimetria_ombro', 'assimetria_escapulas', 'hemi_torax', 'cintura', 'teste_fukuda_deslocamento', 'teste_fukuda_rotacao', 'teste_fukuda_desvio', 'habilidade_ocular_direito', 'habilidade_ocular_esquerdo', 'romberg_mono_direito', 'romberg_mono_esquerdo', 'romberg_sensibilizado_direito', 'romberg_sensibilizado_esquerdo', 'balanco_direito', 'balanco_esquerdo', 'retracao_posterior', 'teste_thomas_direito', 'teste_thomas_esquerdo', 'retracao_peitoral_direito', 'retracao_peitoral_esquerdo', 'forca_muscular_abs', 'forca_ext_tronco', 'resistencia_extensores_tronco'
    ];

    public function plano_frontal()
    {
        return $this->hasMany('App\PlanoFrontal');
    }
    
    public function plano_horizontal()
    {
        return $this->hasMany('App\PlanoHorizontal');
    }
    
    public function plano_sagital()
    {
        return $this->hasMany('App\PlanoSagital');
    }

    public function mobilidade_articular()
    {
        return $this->hasMany('App\MobilidadeArticular');
    }
}
