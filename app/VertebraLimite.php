<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VertebraLimite extends Model
{
    protected $table = 'vertebra_limite';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_escoliose', 'vertebra_superior', 'vertebra_inferior'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'id', 'diagnostico_prognostico_id'
    ];
}
