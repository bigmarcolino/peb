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
        'diagnostico_clinico', 'tipo_escoliose', 'cifose', 'lordose', 'prescricao_medica', 'prescricao_fisioterapeutica', 'colete', 'colete_hs', 'etiologia', 'idade_aparecimento', 'topografia', 'calco', 'hpp'
    ];
}