<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'paciente';

    protected $primaryKey = 'cpf';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cpf', 'nome', 'end_res', 'data_nasc', 'estado', 'cidade', 'cep', 'tel_res', 'tel_trab', 'medico', 'celular', 'indicacao', 'identidade', 'email'
    ];
}
