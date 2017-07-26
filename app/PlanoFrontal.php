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
}
