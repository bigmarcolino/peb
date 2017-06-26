<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FotoPaciente extends Model
{
	protected $table = 'foto_paciente';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome_foto', 'data_foto', 'foto', 'descricao'
    ];
}
