<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deporte extends Model
{
  protected $table = "deporte";
  public $timestamps = false;

  protected $fillable = [
      'nombre'
  ];

  public function socios(){
    return $this->belongsToMany('App\Socio' , 'sociodeporte', 'idDeporte', 'idSocio');
  }
}
