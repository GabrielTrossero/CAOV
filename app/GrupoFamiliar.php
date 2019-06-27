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
}
