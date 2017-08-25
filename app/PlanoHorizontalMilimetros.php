<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanoHorizontalMilimetros extends Model
{
	protected $table = 'plano_horizontal_milimetros';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vertebra', 'valor', 'tipo', 'calco_utilizado'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'id', 'medidas_id'
    ];
}
