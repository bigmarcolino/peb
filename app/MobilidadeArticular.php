<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobilidadeArticular extends Model
{
    protected $table = 'mobilidade_articular';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'valor_reto_direita', 'valor_reto_esquerda', 'valor_inclinado_direita', 'valor_inclinado_esquerda', 'diferenca_direita', 'diferenca_esquerda' 
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'id', 'medidas_id'
    ];
}
