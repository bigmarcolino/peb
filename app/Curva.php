<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curva extends Model
{   
    protected $table = 'curva';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ordenacao', 'observacao', 'angulo_cobb', 'angulo_ferguson', 'grau_rotacao'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'id', 'diagnostico_prognostico_id'
    ];
}
