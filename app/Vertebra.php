<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vertebra extends Model
{
    protected $table = 'vertebra';

    protected $primaryKey = 'vertebra_tipo';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vertebra_tipo', 'local', 'altura', 'vertebra_nome'
    ];
}
