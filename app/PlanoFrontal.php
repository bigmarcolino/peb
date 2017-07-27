<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanoFrontal extends Model
{
	protected $table = 'plano_frontal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'calco', 'valor'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'id', 'medidas_id'
    ];
}
