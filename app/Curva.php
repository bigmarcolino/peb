<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curva extends Model
{   
    protected $table = 'curva';

    protected $primaryKey = 'curva_ordenacao';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'curva_ordenacao', 'tipo', 'angulo_cobb', 'angulo_ferguson', 'grau_rotacao'
    ];
}
