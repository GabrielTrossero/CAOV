<?php
namespace App\Traits;

use Carbon\Carbon;

trait compruebaCadete {
    
    /**
     * Defino con la fecha de nacimiento si el socio es cadete
     * 
     * Aclaración: se toman como cadetes a los socios que tengan hasta 16 años,
     * inclusive si dicho año cumplen 16, se los tomará todo el año como cadetes.
     * 
     * @param Date
     * @return Boolean
     */
    private function isCadete($fechaNac){
        //le resto un año a la fecha actual para que se cumpla la condición de tomar todo
        //el año como cadete, aún si este año llega a 17
        $fechaActual = Carbon::now()->subYear(1)->year;

        $edad = $fechaActual - Carbon::parse($fechaNac)->year;

        if($edad <= 16){
          return true;
        }
        else{
          return false;
        }
    }

}