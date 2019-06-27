<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedioDePago extends Model
{
    protected $table = "mediodepago";
    public $timestamps = false;

    protected $fillable = [
        'nombre'
    ];
}
