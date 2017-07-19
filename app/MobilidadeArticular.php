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
        'lado', 'valor', 'inclinacao'
    ];
}
