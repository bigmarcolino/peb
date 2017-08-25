<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiagnosticoPrognostico extends Model
{
	protected $table = 'diagnostico_prognostico';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'diagnostico_clinico', 'tipo', 'local_escoliose', 'cifose', 'lordose', 'prescricao_medica', 'prescricao_fisioterapeutica', 'colete', 'colete_hs', 'etiologia', 'idade_aparecimento', 'topografia', 'calco_utilizado_direito', 'calco_utilizado_esquerdo', 'tamanho_calco_direito', 'tamanho_calco_esquerdo', 'hpp'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'id', 'atendimento_id'
    ];

    public function curva()
    {
        return $this->hasMany('App\Curva');
    }

    public function vertebra_apice()
    {
        return $this->hasOne('App\VertebraApice');
    }

    public function vertebra_limite()
    {
        return $this->hasOne('App\VertebraLimite');
    }
}
