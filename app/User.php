<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Persona;
use App\TipoUsuario;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = "users";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'idPersona', 'idTipoUsuario'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    //devuelve el tipo de usuario
    public function tipoUsuario(){
      return $this->belongsTo('App\TipoUsuario', 'idTipoUsuario');
    }

    //devuelve la persona del usuario
    public function persona(){
      return $this->belongsTo('App\Persona', 'idPersona');
    }

    //relacion a movimientos extras
    public function movimientosExtras(){
      return $this->hasMany('App\MovExtras' , 'idUser');
    }
}
