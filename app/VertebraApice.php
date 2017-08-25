<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VertebraApice extends Model
{
    protected $table = 'vertebra_apice';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_escoliose', 'vertebra_nome'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'id', 'diagnostico_prognostico_id'
    ];
}
