<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Deporte;

class SocioDeporte extends Model
{
    protected $table = "sociodeporte";
    public $timestamps = false;

    protected $fillable = [
        'idDeporte', 'idSocio'
    ];

    public function deporte(){
      return $this->belongsTo('App\Deporte', 'idDeporte');
    }

    public function socio(){
      return $this->belongsTo('App\Socio', 'idSocio');
    }
}
