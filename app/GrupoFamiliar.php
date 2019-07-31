<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrupoFamiliar extends Model
{
    protected $table = "grupofamiliar";
    public $timestamps = false;

    protected $fillable = [
        'titular', 'pareja'
    ];

    //relacion a titular
    public function socioTitular(){
      return $this->belongsTo('App\Socio', 'titular');
    }

    //relacion a socios
    public function socios(){
      return $this->hasMany('App\Socio', 'idGrupoFamiliar');
    }

    //relacion a pareja
    public function socioPareja(){
      return $this->belongsTo('App\Socio', 'pareja');
    }
}
