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
      //busco la cuota
      $cuota = ComprobanteCuota::find($id);

      $cuota->mesesAtrazados = $this->mesesAtrazados($cuota);
      $cuota->montoInteresAtrazo = $this->montoInteresAtrazo($cuota);

      $cuota->compruebaCuota = $this->comprobarCuota($cuota);

      $cuota->montoInteresGrupoFamiliar = $this->montoInteresGrupoFamiliar($cuota);

      //se lo envío a la vista
      return view('pagoCuota.ingresarPago', ['cuota' => $cuota]);
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
        //busco la cuota que estoy cargando
        $comprobanteCuota = ComprobanteCuota::find($request->id);

        //para redirigir si el socio quiere pagar una cuota inhabilitada
        if($comprobanteCuota->inhabilitada == true){
          return redirect()->back()->withInput()->with('errorInhabilitada', 'ERROR: no puede pagar una cuota que se encuentra inhabilitada.');
        }
        //para redirigir si el socio quiere pagar una cuota que ya está pagada
        else if($comprobanteCuota->fechaPago != null){
          return redirect()->back()->withInput()->with('errorPagada', 'ERROR: no puede pagar una cuota que ya está pagada.');
        }


        //mensajes de error que se mostraran por pantalla
        $messages = [
          'fechaPago.required' => 'Es necesario ingresar una Fecha de Pago.',
          'medioPago.required' => 'Es necesario ingresar un Medio de Pago.',
          'medioPago.in' => 'El Medio de Pago ingresado es incorrecto.'
        ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(), [
          'fechaPago' => 'required',
          'medioPago' => 'required|in:1'
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withInput()->withErrors($validacion->errors());
        }

        //cargo los datos de ComprobanteCuota
        $comprobanteCuota->fechaPago = $request->fechaPago;
        $comprobanteCuota->idMedioDePago = $request->medioPago;

        $comprobanteCuota->save();

        //redirijo para mostrar la cuota ingresada
        return redirect()->action('CuotaController@getShowId', $comprobanteCuota->id);
      }

      //calculo la diferencia de meses entre el mes correspondiente y el pago
      private function mesesAtrazados($cuota){
        if ($cuota->fechaMesAnio < $cuota->fechaPago) {
          $date = Carbon::parse($cuota->fechaMesAnio);
          $now = Carbon::parse($cuota->fechaPago);
          return $date->diffInMonths($now);
        }
        else {
          return 0;
        }
      }

      //calculo el monto a pagar por intereses
      private function montoInteresAtrazo($cuota){
        //si la diferencia de meses entre fechaMesAnio y el pagoCuota es > que la cantidad de meses máxima permitida de atrazo => se cobra intereses
        if ($this->mesesAtrazados($cuota) > $cuota->montoCuota->cantidadMeses) {
          $montoPagar = ($this->mesesAtrazados($cuota) - $cuota->montoCuota->cantidadMeses) * $cuota->montoCuota->montoInteresMensual;
          return $montoPagar;
        }
        else {
          return 0;
        }
      }
/*
      private function integrantesGrupoFamiliar($cuota){
        if ($cuota->socio->grupoFamiliar != null) {
          $idGrupoFamiliar = $cuota->socio->grupoFamiliar->id;
          $cantidadIntegrantes = Socio::where('idGrupoFamiliar', $idGrupoFamiliar)->count();
        }
        else {
          $cantidadIntegrantes = '0';
        }

        return $cantidadIntegrantes;
        }
*/
      //calculo el monto a pagar por cantidad de integrantes
      private function montoInteresGrupoFamiliar($cuota){
        //para que no evalue las cuotas que no son de grupo familiar
        if ($cuota->montoCuota->tipo != 'g') {
          return 0;
        }

        //si la cantidad de integrantes registrada es > que la cantidad de integrantes mínima => cobro
        if ($cuota->cantidadIntegrantes > $cuota->montoCuota->cantidadIntegrantes) {
          $montoPagar = ($cuota->cantidadIntegrantes - $cuota->montoCuota->cantidadIntegrantes) * $cuota->montoCuota->montoInteresGrupoFamiliar;
          return $montoPagar;
        }
        else {
          return 0;
        }
      }

      //comprueba que la cuota que se quiera pagar sea la "más antigua", esto es por como está diseñado el sistema de pagos
      private function comprobarCuota($cuota){
        //obtengo la cuota más vieja
        $cuotaQueDeberiaPagar = ComprobanteCuota::where('idSocio', $cuota->idSocio)->where('fechaPago', null)->where('inhabilitada', false)->orderBy('fechaMesAnio', 'ASC')->first();

        //si la que estoy por pagar es igual a la que debería pagar (la más vieja), entonces está bien
        if ($cuota->id == $cuotaQueDeberiaPagar->id) {
          return true;
        }
        else {
          return false;
        }
      }

      //le agrego al socio los montos de cada categoría
      private function asignarMontos($socio){

        $monto = MontoCuota::select('montoMensual')->where('tipo', 'a')->orderBy('fechaCreacion', 'DESC')->first();
        $socio->montoActivo = $monto['montoMensual'];

        $monto = MontoCuota::where('tipo', 'g')->orderBy('fechaCreacion', 'DESC')->first();
        $socio->montoGrupoFamiliar = $monto['montoMensual'];
        $socio->montoCuotaInteresGrupoFamiliar = $monto['montoInteresGrupoFamiliar'];
        $socio->montoCuotaCantidadIntegrantes = $monto['cantidadIntegrantes'];

        $monto = MontoCuota::select('montoMensual')->where('tipo', 'c')->orderBy('fechaCreacion', 'DESC')->first();
        $socio->montoCadete = $monto['montoMensual'];

        return $socio;
      }
}
