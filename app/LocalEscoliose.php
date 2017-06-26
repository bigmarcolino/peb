<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocalEscoliose extends Model
{
    protected $table = 'local_escoliose';

    protected $primaryKey = 'local';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'local', 'lado'
    ];
}
