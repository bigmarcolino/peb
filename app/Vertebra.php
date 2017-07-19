<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vertebra extends Model
{
    protected $table = 'vertebra';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo', 'local', 'altura', 'vertebra_nome'
    ];
}
