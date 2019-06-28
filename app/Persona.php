<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = "persona";
    public $timestamps = false;

    protected $fillable = [
        'apellido', 'DNI', 'domicilio', 'email', 'nombres', 'telefono'
    ];

    //relacion a reservas de muebles
    public function reservasDeMueble(){
      return $this->hasMany('App\ReservaMueble', 'idPersona');
    }

    //relacion a socio
    public function socio(){
      return $this->hasOne('App\Socio', 'idPersona');
    }

    //relacion a users
    public function user(){
      return $this->hasOne('App\User', 'idPersona');
    }

    //relacion a reservas de inmuebles
    public function reservasDeInmueble(){
      return $this->hasMany('App\ReservaInmueble', 'idPersona');
    }
}
