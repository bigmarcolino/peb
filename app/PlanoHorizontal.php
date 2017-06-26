<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanoHorizontal extends Model
{
	protected $table = 'plano_horizontal';

    protected $primaryKey = 'vertebra';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vertebra', 'valor', 'unidade', 'calco'
}
