<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovExtras extends Model
{
    protected $table = "movextras";
    public $timestamps = false;

    protected $fillable = [
        'descripcion', 'fecha', 'idUser', 'monto', 'numRecibo', 'tipo'
    ];
}
