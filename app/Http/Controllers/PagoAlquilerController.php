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
use App\MovExtras;
use PDF;
use Mail;
use App\Mail\SendMail;

class PagoAlquilerController extends Controller
{
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


      //compruebo que el numRecibo no se repita en AlquilerInmueble y en Registros, y en AqluilerMueble compruebo que no haya otro con el mismo numRecibo y ademas con el mismo tipo de idMueble (porque en los alquileres de muebles si se puede repetir el numRecibo)
      $alquileresMueble = ReservaMueble::where('numRecibo', $request->numRecibo)->get();
      $alquileresInmueble = ReservaInmueble::all();
      $registros = MovExtras::all();

      foreach ($alquileresMueble as $alquilerMueble) {
        if ($alquilerMueble->mueble->id == $request->tipoMueble) {
          return redirect()->back()->withInput()->with('validarNumRecibo', 'Error, ya se ha hecho otra reserva de '. $alquilerMueble->mueble->nombre .' para dicho Numero de Recibo');
        }
      }

      foreach ($alquileresInmueble as $alquilerInmueble) {
        if ($alquilerInmueble->numRecibo == $request->numRecibo) {
          return redirect()->back()->withInput()->with('validarNumRecibo', 'Error, dicho Número de Recibo ha sido usado en un Alquiler de Inmueble.');
        }
      }

      foreach ($registros as $registro) {
        if ($registro->numRecibo == $request->numRecibo) {
          return redirect()->back()->withInput()->with('validarNumRecibo', 'Error, dicho Número de Recibo ha sido usado en otro Registro.');
        }
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
      }

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



      //compruebo que el numRecibo no se repita
      $alquileresMueble = ReservaMueble::all();
      $alquileresInmueble = ReservaInmueble::all();
      $registros = MovExtras::all();

      foreach ($alquileresMueble as $alquilerMueble) {
        if ($alquilerMueble->numRecibo == $request->numRecibo) {
          return redirect()->back()->withInput()->with('validarNumRecibo', 'Error, dicho Número de Recibo ha sido usado en un Alquiler de Mueble.');
        }
      }

      foreach ($alquileresInmueble as $alquilerInmueble) {
        if ($alquilerInmueble->numRecibo == $request->numRecibo) {
          return redirect()->back()->withInput()->with('validarNumRecibo', 'Error, dicho Número de Recibo ha sido usado en un Alquiler de Inmueble.');
        }
      }

      foreach ($registros as $registro) {
        if ($registro->numRecibo == $request->numRecibo) {
          return redirect()->back()->withInput()->with('validarNumRecibo', 'Error, dicho Número de Recibo ha sido usado en otro Registro.');
        }
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

      //redirijo a la vista individual
      return redirect()->action('AlquilerInmuebleController@getShowId', $request->id);

    }

    /**
     * genera el pdf para el id de la reserva del inmueble pagada dada
     *
     * @param Request $request
     *
     * @return PDF
     */
    public function generarPdfInmueble($id) {
      //tomo la reserva pagada
      $reservaPagada = ReservaInmueble::find($id);

      $pdf = PDF::loadView('pdf.comprobantes.inmueble', ['recibo' => $reservaPagada]);

      return $pdf->download('comprobante-alquiler-inmueble.pdf');
    }

    /**
     * genera el pdf para el id de la reserva del mueble pagada dada
     *
     * @param Request $request
     *
     * @return PDF
     */
    public function generarPdfMueble($id) {
      //tomo la reserva pagada
      $reservaPagada = ReservaMueble::find($id);

      $pdf = PDF::loadView('pdf.comprobantes.mueble', ['recibo' => $reservaPagada]);

      return $pdf->download('comprobante-alquiler-mueble.pdf');
    }
}
