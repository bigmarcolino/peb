<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanoSagital extends Model
{
    protected $table = 'plano_sagital';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'valor_cabeca', 'valor_cervical', 'valor_c7', 'valor_t5_t6', 'valor_t12', 'valor_l3', 'valor_s1', 'compensacao_cabeca', 'compensacao_cervical', 'compensacao_c7', 'compensacao_t5_t6', 'compensacao_t12', 'compensacao_l3', 'compensacao_s1'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'id', 'medidas_id'
    ];
}
