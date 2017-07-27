<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanoHorizontal extends Model
{
	protected $table = 'plano_horizontal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vertebra', 'valor', 'tipo', 'calco'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'id', 'medidas_id'
    ];
}
