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
use PDF;
use Mail;
use App\Mail\SendMail;

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

      //tomo la reserva pagada
      $reservaPagada = ReservaMueble::find($request->id);
      
      //envío de mail con el detalle del recibo del alquiler del mueble
      if (isset($reservaPagada->persona->email)) {
        $numSocio = null;
        if($reservaPagada->persona->socio) {
          $numSocio = $reservaPagada->persona->socio->numSocio;
        }

        $arrayReserva = array(
          'emailTo' => $reservaPagada->persona->email,
          'muebleNombre' => $reservaPagada->mueble->nombre,
          'fechaSolicitud' => $reservaPagada->fechaSolicitud,
          'apellido_nombres' => $reservaPagada->persona->apellido.", ".$reservaPagada->persona->nombres,
          'domicilio' => $reservaPagada->persona->domicilio,
          'DNI' => $reservaPagada->persona->DNI,
          'numSocio' => $numSocio,
          'fechaHoraInicio' => $reservaPagada->fechaHoraInicio,
          'fechaHoraFin' => $reservaPagada->fechaHoraFin,
          'cantidad' => $reservaPagada->cantidad,
          'costoTotal' => $reservaPagada->costoTotal,
          'observacion' => $reservaPagada->observacion,
          'numRecibo' => $reservaPagada->numRecibo
        );

        Mail::to($arrayReserva['emailTo'])->send(new SendMail($arrayReserva, 'mueble'));
        
        /* NO USADO
        Mail::send('emails.mueble', ['data' => $arrayReserva], function ($message) use ($arrayReserva) {
          $message->to($arrayReserva['emailTo'])->from('dreherfrancisco@gmail.com')->subject('Hola');
        });
        */

        /* NO USADO
        Mail::send('emails.mueble', $arrayReserva, function ($message) use ($arrayReserva) {
          $message->from('comprobantes.caov@gmail.com', 'Club Atlético Oro Verde');
          $message->to($arrayReserva['emailTo']);
          $message->subject('Pago de Alquiler de Muebles'); 
        });*/
      }
      
      $pdf = PDF::loadView('pdf.comprobantes.mueble', ['recibo' => $reservaPagada]);

      return $pdf->download('comprobante-alquiler-mueble.pdf');

      /*
      //redirijo a la vista individual
      return redirect()->action('AlquilerMuebleController@getShowId', $request->id);
      */
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

      //tomo la reserva pagada
      $reservaPagada = ReservaInmueble::find($request->id);

      //envío de mail con el detalle del recibo del alquiler del inmueble
      if (isset($reservaPagada->persona->email)) {
        $numSocio = null;
        if($reservaPagada->persona->socio) {
          $numSocio = $reservaPagada->persona->socio->numSocio;
        }

        $arrayReserva = array(
          'emailTo' => $reservaPagada->persona->email,
          'inmuebleNombre' => $reservaPagada->inmueble->nombre,
          'fechaSolicitud' => $reservaPagada->fechaSolicitud,
          'apellido_nombres' => $reservaPagada->persona->apellido.", ".$reservaPagada->persona->nombres,
          'domicilio' => $reservaPagada->persona->domicilio,
          'DNI' => $reservaPagada->persona->DNI,
          'numSocio' => $numSocio,
          'fechaHoraInicio' => $reservaPagada->fechaHoraInicio,
          'fechaHoraFin' => $reservaPagada->fechaHoraFin,
          'tipoEvento' => $reservaPagada->tipoEvento,
          'cantAsistentes' => $reservaPagada->cantAsistentes,
          'tieneServicioLimpieza' => $reservaPagada->tieneServicioLimpieza,
          'tieneMusica' => $reservaPagada->tieneMusica,
          'tieneReglamento' => $reservaPagada->tieneReglamento,
          'costoTotal' => $reservaPagada->costoTotal,
          'numRecibo' => $reservaPagada->numRecibo
        );

        Mail::to($arrayReserva['emailTo'])->send(new SendMail($arrayReserva, 'inmueble'));
      }
      
      $pdf = PDF::loadView('pdf.comprobantes.inmueble', ['recibo' => $reservaPagada]);

      return $pdf->download('comprobante-alquiler-inmueble.pdf');
      
      /*
      //redirijo a la vista individual
      return redirect()->action('AlquilerInmuebleController@getShowId', $request->id);
      */
    }
}
