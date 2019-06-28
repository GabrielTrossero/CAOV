<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrupoFamiliar extends Model
{
    protected $table = "grupofamiliar";
    public $timestamps = false;

    protected $fillable = [
        'titular'
    ];

    //relacion a titular
    public function titular(){
      return $this->belongsTo('App\Socio', 'titular');
    }

    //relacion a socios
    public function socios(){
      return $this->hasMany('App\Socio', 'idGrupoFamiliar');
    }
}
