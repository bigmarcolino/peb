<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobilidadeArticular extends Model
{
    protected $table = 'mobilidade_articular';

    protected $primaryKey = 'lado';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lado', 'valor', 'inclinacao'
    ];
}
