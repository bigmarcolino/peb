<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanoSagital extends Model
{
    protected $table = 'plano_sagital';

    protected $primaryKey = 'localizacao';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'localizacao', 'valor', 'diferenca'
    ];
}
