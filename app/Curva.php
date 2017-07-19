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
        'ordenacao', 'tipo', 'angulo_cobb', 'angulo_ferguson', 'grau_rotacao'
    ];
}
