<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanoFrontal extends Model
{
	protected $table = 'plano_frontal';

    protected $primaryKey = 'calco';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'calco', 'valor'
}
