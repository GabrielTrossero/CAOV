<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inmueble extends Model
{
    protected $table = "inmueble";
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'descripcion'
    ];

    //relacion a reservas de inmuebles
    public function reservasDeInmueble(){
      return $this->hasMany('App\ReservaInmueble', 'idInmueble');
    }
}
