<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Socio;
use App\ComprobanteCuota;
use App\MontoCuota;

class PagoCuotaController extends Controller
{
  /**
   * Display the list of Socios to choose who paids the Cuota.
   *
   * @return \Illuminate\Http\Response
   */
  public function getShow()
  {
    //obtengo todos los socios
    $socios = Socio::all();

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

      //montocuota?

      //se lo envío a la vista
      return view('pagoCuota.ingresarPago', ['socio' => $socio]);
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

        $socio = Socio::find($request->id);

        if($socio->idGrupoFamiliar){
          $montoCuota = MontoCuota::where('tipo', 'g')->orderBy('fechaCreacion','DESC')->first();
        }
        //HACER PARA CADETE Y ACTIVO UNA VEZ QUE TENGA LA EDAD

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
