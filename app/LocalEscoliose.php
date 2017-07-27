<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocalEscoliose extends Model
{
    protected $table = 'local_escoliose';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'local', 'lado'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'id', 'diagnostico_prognostico_id'
    ];
}
