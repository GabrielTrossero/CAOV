<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Socio;
use App\ComprobanteCuota;
use App\MontoCuota;
use Carbon\Carbon;

class PagoCuotaController extends Controller
{
  /**
   * calcula la edad del socio ingresado por parametro
   * @param  App\Socio $socio
   * @return App\Socio
   */
  private function calculaUltimoMes($socio)
  {
      //busco el último mes que pagó tal socio
      $ultimoMes = ComprobanteCuota::select('fechaMesAnio')->where('idSocio', $socio->id)->orderBy('fechaMesAnio', 'DESC')->first();

      //si ha pagado cuotas, asigno al atributo ultimoMesPagado lo que recuperé anteriormente
      if($ultimoMes){
        $socio->ultimoMesPagado = $ultimoMes->fechaMesAnio;
      }
      //sino le asigno null
      else{
        $socio->ultimoMesPagado = null;
      }

      //retorna al socio con su último mes pagado
      return $socio;
  }

  /**
   * Display the list of Socios to choose who paids the Cuota.
   *
   * @return \Illuminate\Http\Response
   */
  public function getShow()
  {
    //obtengo todos los socios
    $socios = Socio::all();

    foreach ($socios as $socio) {
      $socio = $this->calculaUltimoMes($socio);
    }

    //se los envio a la vista
    return view('pagoCuota.listarSocios', compact('socios'));
  }

/**
   * Display the the form to add the Socios's payment.
   * @param int $id
   * @return \Illuminate\Http\Response
   */
    public function getPago($id)
    {
      //busco el socio
      $socio = Socio::find($id);

      //se lo envío a la vista
      return view('pagoCuota.ingresarPago', ['socio' => $socio]);
    }

    /**
     * calcula la edad del socio ingresado por parametro
     * @param  App\Socio $socio
     * @return App\Socio
     */
    private function calculaEdad($socio)
    {
        /*retorno la edad del socio
        return Carbon::parse($socio->fechaNac)->age;
        */

        // calcula la edad del socio segun su categoria
        $edad = Carbon::now()->year - Carbon::parse($socio->fechaNac)->year;
      
        //retorna la edad del socio
        return $edad;
    }

    /**
     * Add the payment and generate a pdf or send it via email.
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
      public function postPago(Request $request)
      {
        $comprobanteCuota = new ComprobanteCuota;

        //mensajes de error que se mostraran por pantalla
        $messages = [
          'fechaPago.required' => 'Es necesario ingresar una Fecha de Pago.',
          'fechaMesAnio.required' => 'Es necesario ingresar un Mes y Año correspondinte.',
          'medioPago.required' => 'Es necesario ingresar un Medio de Pago.',
          'medioPago.in' => 'El Medio de Pago ingresado es incorrecto.',
          'tipo.required' => 'Es necesario ingresar un Tipo.',
          'tipo.in' => 'El Tipo ingresado es incorrecto.'
        ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(), [
          'fechaPago' => 'required',
          'fechaMesAnio' => 'required',
          'medioPago' => 'required|in:1',
          'tipo' => 'required|in:m,s,a'
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withInput()->withErrors($validacion->errors());
        }

        //busco el socio que estoy cargando
        $socio = Socio::find($request->id);

        //y de acuerdo con el socio obtendio, obtengo el MontoCuota correspondinte a la categoría del socio
        if($socio->idGrupoFamiliar){
          $montoCuota = MontoCuota::where('tipo', 'g')->orderBy('fechaCreacion','DESC')->first();
        }
        else if($this->calculaEdad($socio) >= 18){
          $montoCuota = MontoCuota::where('tipo', 'a')->orderBy('fechaCreacion','DESC')->first();
        }
        else if($this->calculaEdad($socio) <= 18){
          $montoCuota = MontoCuota::where('tipo', 'c')->orderBy('fechaCreacion','DESC')->first();
        }

        //cargo los datos de ComprobanteCuota
        $comprobanteCuota->tipo = $request->tipo;
        $comprobanteCuota->fechaMesAnio = $request->fechaMesAnio;
        $comprobanteCuota->fechaPago = $request->fechaPago;
        $comprobanteCuota->idMontoCuota = $montoCuota->id;
        $comprobanteCuota->idMedioDePago = $request->medioPago;
        $comprobanteCuota->idSocio = $request->id;

        $comprobanteCuota->save();

        //redirijo para mostrar la cuota ingresada
        return redirect()->action('CuotaController@getShowId', $comprobanteCuota->id);
      }
}
