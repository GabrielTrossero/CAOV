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

    //relacion a usuario
    public function user(){
      return $this->belongsTo('App\User', 'idUser');
    }
}
