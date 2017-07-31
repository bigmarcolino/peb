<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
	protected $table = 'atendimento';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idade_cronologica', 'idade_ossea', 'menarca', 'altura', 'altura_sentada', 'peso', 'risser', 'data_raio_x'
    ];

    protected $hidden = [
        'updated_at', 'id', 'cpf_paciente'
    ];

    public function medidas()
    {
        return $this->hasOne('App\Medidas');
    }

    public function diag_prog()
    {
        return $this->hasOne('App\DiagnosticoPrognostico');
    }
}
