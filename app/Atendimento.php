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
        'idade_cronologica', 'idade_ossea', 'menarca', 'num_atendimento', 'data_atendimento', 'altura', 'altura_sentada', 'peso', 'risser', 'data_raio_x'
    ];

    public function paciente()
    {
        return $this->hasOne('App\Paciente', 'cpf_paciente_fk');
    }

    public function medidas()
    {
        return $this->hasOne('App\Medidas', 'id_medidas_fk');
    }
}
