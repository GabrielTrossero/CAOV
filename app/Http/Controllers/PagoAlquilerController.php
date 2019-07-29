<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\ReservaMueble;
use App\Mueble;
use App\MedioDePago;
use App\ReservaInmueble;
use App\Inmueble;

class PagoAlquilerController extends Controller
{
    /**
    * Display the list of Alquileres to choose which of them will be paid.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
      return view('pagoAlquiler.menu');
    }

    /**
    * Display the resource list
    *
   * @return \Illuminate\Http\Response
    */
    public function getShowMueble()
    {
      //obtengo todas las reservas, menos las que ya tienen N° de recibo, o sea, están pagadas
      $alquileresMuebles = ReservaMueble::where('numRecibo', null)->get();

      return view('pagoAlquiler.listaMuebles', compact('alquileresMuebles'));
    }

    /**
    * Display the resource list
    *
    * @return \Illuminate\Http\Response
    */
    public function getShowInmueble()
    {
      //obtengo todas las reservas, menos las que ya tienen N° de recibo, o sea, están pagadas
      $reservasInmuebles = ReservaInmueble::where('numRecibo', null)->get();

      return view('pagoAlquiler.listaInmuebles', compact('reservasInmuebles'));
    }

    /**
    * Show the options on the specified resource
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function getPagoMueble($id)
    {
      //obtengo todos los muebles
      $muebles = Mueble::all();

      //obtnego todos los medios de pagos
      $mediosDePagos = MedioDePago::all();

      //obtengo la reserva a editar
      $reserva = ReservaMueble::find($id);

      return view('pagoAlquiler.ingresarPagoMueble', compact(['muebles','mediosDePagos','reserva']));
    }

    /**
     * Add the payment and generate a pdf or send it via email.
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postPagoMueble(Request $request)
    {
      $reserva = new ReservaMueble;

      //mensajes de error que se mostraran por pantalla
      $messages = [
        'numRecibo.required' => 'Es necesario ingresar un N° de Recibo.',
      ];

      //valido los datos ingresados
      $validacion = Validator::make($request->all(), [
        'numRecibo' => 'required'
      ], $messages);

      //si la validacion falla vuelvo hacia atras con los errores
      if($validacion->fails()){
        return redirect()->back()->withInput()->withErrors($validacion->errors());
      }

      //actualizo dicho registro
      ReservaMueble::where('id', $request->id)
            ->update([
              'numRecibo' => $request->numRecibo
            ]);

      //redirijo a la vista individual
      return redirect()->action('AlquilerMuebleController@getShowId', $request->id);
    }

    /**
    * Show the options on the specified resource
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function getPagoInmueble($id)
    {
      //tomo la reserva del inmueble
      $reservaInmueble = ReservaInmueble::find($id);

      //tomo todos los inmuebles
      $inmuebles = Inmueble::all();

      //tomo los medios de pago
      $mediosDePago = MedioDePago::all();

      return view('pagoalquiler.ingresarPagoInmueble', compact('reservaInmueble', 'inmuebles', 'mediosDePago'));
    }

    /**
     * Add the payment and generate a pdf or send it via email.
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postPagoInmueble(Request $request)
    {
      $reserva = new ReservaInmueble;

      //mensajes de error que se mostraran por pantalla
      $messages = [
        'numRecibo.required' => 'Es necesario ingresar un N° de Recibo.',
      ];

      //valido los datos ingresados
      $validacion = Validator::make($request->all(), [
        'numRecibo' => 'required'
      ], $messages);

      //si la validacion falla vuelvo hacia atras con los errores
      if($validacion->fails()){
        return redirect()->back()->withInput()->withErrors($validacion->errors());
      }

      //actualizo dicho registro
      ReservaInmueble::where('id', $request->id)
            ->update([
              'numRecibo' => $request->numRecibo
            ]);

      //redirijo a la vista individual
      return redirect()->action('AlquilerInmuebleController@getShowId', $request->id);
    }
}
