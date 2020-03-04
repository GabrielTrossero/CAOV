<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use App\Inmueble;
use App\ReservaInmueble;
use App\ReservaMueble;
use App\MovExtras;
use App\MedioDePago;
use App\Persona;
use Carbon\Carbon;

class AlquilerInmuebleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('alquilerinmueble.menu');
    }

    /**
     * Devuelve información acerca de la disponibilidad de horarios en tal fecha
     *
     *
     */
    public function postDisponibilidad(){
      $inmuebleSeleccionado = Input::get('inmueble');
      $alquileres = ReservaInmueble::all()->where('idInmueble', $inmuebleSeleccionado);
      $fecha = Carbon::parse(Input::get('fecha'))->format('Y-m-d');

      $fechasReservadas = array();

      foreach($alquileres as $alquiler){
        $alquiler->soloFecha = Carbon::parse($alquiler->fechaHoraInicio)->format('Y-m-d');

        if ($alquiler->soloFecha == $fecha) {
          $fechasReservadas[] = array($alquiler->soloFecha, $alquiler->fechaHoraInicio, $alquiler->fechaHoraFin);
        }
      }

      return response()->json(['fechasReservadas' => $fechasReservadas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //tomo los inmuebles
        $inmuebles = Inmueble::all();

        //tomo los medios de pago
        $mediosDePago = MedioDePago::all();

        //tomo todas las personas para mostrarlas en el select
        $personas = Persona::all();

        //redirijo a la vista de agregar con los inmuebles
        return view('alquilerinmueble.agregar', compact('inmuebles', 'mediosDePago', 'personas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $persona = new Persona;

        //mensajes de error que se mostraran por pantalla
        $messages = [
          'idPersona.required' => 'Es necesario ingresar una Persona.',
          'inmueble.required' => 'Es necesario ingresar un Inmueble',
          'fechaSol.required' => 'Es necesario ingresar una Fecha de Solicitud',
          'fechaHoraInicio.required' => 'Es necesario ingresar una Fecha y Hora de Inicio',
          'fechaHoraFin.required' => 'Es necesario ingresar una Fecha y Hora de Finalización',
          'observacion.max' => 'Ingrese una Observacion de menos de 100 caracteres',
          'costoReserva.required' => 'Es necesario ingresar un Costo de Reserva',
          'costoReserva.regex' => 'Es necesario ingresar un Costo de Reserva positivo mayor o igual a 0 (cero)',
          'costoTotal.required' => 'Es necesario ingresar un Costo Total',
          'costoTotal.regex' => 'Es necesario ingresar un Costo Total positivo mayor a 0 (cero)',
          'medioPago.required' => 'Es necesario ingresar un Medio de Pago',
          'medioPago.in' => 'Ingrese valores de Medio de Pago válidos',
          'tipoEvento.required' => 'Es necesario ingresar el Tipo de Evento a realizar',
          'tipoEvento.max' => 'Ingrese un Tipo de Evento de menos de 75 caracteres',
          'cantAsistentes.required' => 'Es necesario ingresar una Cantidad de Asistentes',
          'cantAsistentes.regex' => 'Ingrese una Cantidad de Asistentes válida',
          'servicioLimp.required' => 'Es necesario ingresar si posee Servicio de Limpieza',
          'servicioLimp.in' => 'Ingrese valores válidos para Servicio de Limpieza',
          'musica.required' => 'Es necesario ingresar si posee Música',
          'musica.in' => ' Ingrese valores válidos para Música',
          'reglamento.required' => 'Es necesario ingresar si posee Reglamento',
          'reglamento.in' => 'Ingrese valores válidos para Reglamento'
        ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(),[
          'idPersona' => 'required',
          'inmueble' => 'required',
          'fechaSol' => 'required',
          'fechaHoraInicio' => 'required',
          'fechaHoraFin' => 'required',
          'observacion' => 'max:100',
          'costoReserva' => 'required|regex:/^[0-9]+/',
          'costoTotal' => 'required|regex:/^[1-9][0-9]+/|not_in:0',
          'medioPago' =>'required|in:1',
          'tipoEvento' => 'required|max:75',
          'cantAsistentes' => 'required|regex:/^[1-9][0-9]+/|not_in:0',
          'servicioLimp' => 'required|in:0,1',
          'musica' => 'required|in:0,1',
          'reglamento' => 'required|in:0,1'
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withInput()->withErrors($validacion->errors());
        }


        //obtengo la persona correspondiente
        $persona = Persona::where('id', $request->idPersona)->first();

        //valido que la persona exista
        if (!isset($persona)) {
          return redirect()->back()->withInput()->with('validarPersonaExiste', 'Error al seleccionar la Persona.');
        }


        //valido si la fecha y hora de finalizacion es menor a la de inicio
        if ($request->fechaHoraInicio >= $request->fechaHoraFin) {
          return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y Finalización son erróneas, por favor revise las mismas');
        }

        //valido solapamiento entre fechas ingresadas y las fechas y horas de inicio en la BD, del inmueble a alquilar.
        //Por lo tanto obtengo todas las reservas de dicho Inmueble, donde la fechaHora de inicio esté entre la fechaHora de inicio y fin que ingresé
        $solapamientoFechas = ReservaInmueble::where('idInmueble', $request->inmueble)
                                       ->whereBetween('fechaHoraInicio', [$request->fechaHoraInicio, $request->fechaHoraFin])->get();

        if (sizeof($solapamientoFechas) != 0) {
          return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y Finalización se solapan con otra Reserva, por favor revise la misma');
        }

        //valido solapamiento entre fechas ingresadas y las fechas y horas de fin en la BD, del inmueble a alquilar
        //Por lo tanto obtengo todas las reservas de dicho Inmueble, donde la fechaHora de fin esté entre la fechaHora de inicio y fin que ingresé
        $solapamientoFechas = ReservaInmueble::where('idInmueble', $request->inmueble)
                                       ->whereBetween('fechaHoraFin', [$request->fechaHoraInicio, $request->fechaHoraFin])->get();

        if (sizeof($solapamientoFechas) != 0) {
          return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y Finalización se solapan con otra Reserva, por favor revise la misma');
        }

        //valido solapamiento entre fechas ingresadas y las fechas y horas de inicio y fin en la BD, del inmueble a alquilar
        //(si alguna reserva está entre medio de las que ingresó)
        $solapamientoFechas = ReservaInmueble::where('idInmueble', $request->inmueble)
                                       ->where('id', '<>', $request->id)
                                       ->where('fechaHoraInicio', '<=', $request->fechaHoraInicio)
                                       ->where('fechaHoraFin','>=', $request->fechaHoraFin)
                                       ->get();


        if (sizeof($solapamientoFechas) != 0) {
          return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y Finalización se solapan con otra Reserva, por favor revise la misma');
        }


        //valido que el Inmueble exista
        $validarInmueble = Inmueble::where('id', $request->inmueble)->first();

        if (!isset($validarInmueble)) {
          return redirect()->back()->withInput()->with('validarInmueble', 'Error al seleccionar un Inmueble.');
        }


        //valido que el costo de la reserva no sea mayor al del costo total
        if ($request->costoReserva > $request->costoTotal) {
          return redirect()->back()->withInput()->with('validarMonto', 'Error, el costo de la Reserva no puede ser mayor al costo Total del alquiler.');
        }


        //alamceno el nuevo registro en la BD
        $reservaInmueble = new ReservaInmueble;

        $reservaInmueble->fechaSolicitud = $request->fechaSol;
        $reservaInmueble->fechaHoraInicio = $request->fechaHoraInicio;
        $reservaInmueble->fechaHoraFin = $request->fechaHoraFin;
        $reservaInmueble->costoReserva = $request->costoReserva;
        $reservaInmueble->observacion = $request->observacion;
        $reservaInmueble->tieneServicioLimpieza = intval($request->servicioLimp);
        $reservaInmueble->cantAsistentes = intval($request->cantAsistentes);
        $reservaInmueble->numRecibo = NULL;
        $reservaInmueble->tipoEvento = $request->tipoEvento;
        $reservaInmueble->costoTotal = $request->costoTotal;
        $reservaInmueble->tieneMusica = intval($request->musica);
        $reservaInmueble->tieneReglamento = intval($request->reglamento);
        $reservaInmueble->idInmueble = $request->inmueble;
        $reservaInmueble->idPersona = $persona->id;
        $reservaInmueble->idMedioDePago = $request->medioPago;

        $reservaInmueble->save();

        //redirijo para mostrar la reserva ingresada
        return redirect()->action('AlquilerInmuebleController@getShowId', $reservaInmueble->id);
    }

    /**
     * Display the resource list.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function getShow()
    {
        //tomo todos las reservas de inmuebles
        $reservasInmuebles = ReservaInmueble::all();

        //redirijo a la vista con las reservas de inmuebles hechas
        return view('alquilerinmueble.listado', compact('reservasInmuebles'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShowId($id)
    {
        //tomo la reserva segun el id pasado por parametro
        $reservaInmueble = ReservaInmueble::find($id);

        //redirijo a la vista individual de la reserva del inmueble
        return view('alquilerinmueble.individual', compact('reservaInmueble'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //tomo la reserva del inmueble a editar
        $reservaInmueble = ReservaInmueble::find($id);

        //tomo todos los inmuebles
        $inmuebles = Inmueble::all();

        //tomo los medios de pago
        $mediosDePago = MedioDePago::all();

        //tomo todas las personas para mostrarlas en el select
        $personas = Persona::all();

        //redirijo a la vista de editar con la reserva del inmueble
        return view('alquilerinmueble.editar', compact('reservaInmueble', 'inmuebles', 'mediosDePago', 'personas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $persona = new Persona;

      //mensajes de error que se mostraran por pantalla
      $messages = [
        'idPersona.required' => 'Es necesario ingresar una Persona.',
        'inmueble.required' => 'Es necesario ingresar un Inmueble',
        'fechaSol.required' => 'Es necesario ingresar una Fecha de Solicitud',
        'fechaHoraInicio.required' => 'Es necesario ingresar una Fecha y Hora de Inicio',
        'fechaHoraFin.required' => 'Es necesario ingresar una Fecha y Hora de Finalización',
        'observacion.max' => 'Ingrese una Observacion de menos de 100 caracteres',
        'costoReserva.required' => 'Es necesario ingresar un Costo de Reserva',
        'costoReserva.regex' => 'Es necesario ingresar un Costo de Reserva positivo mayor o igual a 0 (cero)',
        'costoTotal.required' => 'Es necesario ingresar un Costo Total',
        'costoTotal.regex' => 'Es necesario ingresar un Costo Total positivo mayor a 0 (cero)',
        'medioPago.required' => 'Es necesario ingresar un Medio de Pago',
        'medioPago.in' => 'Ingrese valores de Medio de Pago válidos',
        'tipoEvento.required' => 'Es necesario ingresar el Tipo de Evento a realizar',
        'tipoEvento.max' => 'Ingrese un Tipo de Evento de menos de 75 caracteres',
        'cantAsistentes.required' => 'Es necesario ingresar una Cantidad de Asistentes',
        'cantAsistentes.regex' => 'Ingrese una Cantidad de Asistentes válida',
        'servicioLimp.required' => 'Es necesario ingresar si posee Servicio de Limpieza',
        'servicioLimp.in' => 'Ingrese valores válidos para Servicio de Limpieza',
        'musica.required' => 'Es necesario ingresar si posee Música',
        'musica.in' => ' Ingrese valores válidos para Música',
        'reglamento.required' => 'Es necesario ingresar si posee Reglamento',
        'reglamento.in' => 'Ingrese valores válidos para Reglamento'
      ];

      //valido los datos ingresados
      $validacion = Validator::make($request->all(),[
        'idPersona' => 'required',
        'inmueble' => 'required',
        'fechaSol' => 'required',
        'fechaHoraInicio' => 'required',
        'fechaHoraFin' => 'required',
        'observacion' => 'max:100',
        'costoReserva' => 'required|regex:/^[0-9]+/',
        'costoTotal' => 'required|regex:/^[1-9][0-9]+/|not_in:0',
        'medioPago' =>'required|in:1,2',
        'tipoEvento' => 'required|max:75',
        'cantAsistentes' => 'required|regex:/^[1-9][0-9]+/|not_in:0',
        'servicioLimp' => 'required|in:0,1',
        'musica' => 'required|in:0,1',
        'reglamento' => 'required|in:0,1'
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withInput()->withErrors($validacion->errors());
        }

        //tomo la reserva del inmueble
        $reservaOriginal = ReservaInmueble::find($request->id);

        //valido si la fecha y hora de finalizacion es menor a la de inicio
        if ($request->fechaHoraInicio >= $request->fechaHoraFin) {
          return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y  Finalización son erróneas, por favor revise las mismas');
        }

        //valido solapamiento entre fechas ingresadas y las fechas y horas de inicio en la BD, del inmueble a alquilar
        //Por lo tanto obtengo todas las reservas de dicho Inmueble (omitiendo la que estoy actualizando), donde la fechaHora de inicio esté entre la fechaHora de inicio y fin que ingresé
        $solapamientoFechas = ReservaInmueble::where('idInmueble', $request->inmueble)
                                       ->where('id', '<>', $request->id)
                                       ->whereBetween('fechaHoraInicio', [$request->fechaHoraInicio, $request->fechaHoraFin])->get();

        if (sizeof($solapamientoFechas) != 0) {
          return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y Finalización se solapan con otra Reserva, por favor revise la misma');
        }

        //valido solapamiento entre fechas ingresadas y las fechas y horas de fin en la BD, del inmueble a alquilar
        //Por lo tanto obtengo todas las reservas de dicho Inmueble(omitiendo la que estoy actualizando), donde la fechaHora de fin esté entre la fechaHora de inicio y fin que ingresé
        $solapamientoFechas = ReservaInmueble::where('idInmueble', $request->inmueble)
                                       ->where('id', '<>', $request->id)
                                       ->whereBetween('fechaHoraFin', [$request->fechaHoraInicio, $request->fechaHoraFin])->get();

        if (sizeof($solapamientoFechas) != 0) {
          return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y Finalización se solapan con otra Reserva, por favor revise la misma');
        }

        //valido solapamiento entre fechas ingresadas y las fechas y horas de inicio y fin en la BD, del inmueble a alquilar
        //(si alguna reserva está entre medio de las que ingresó)
        $solapamientoFechas = ReservaInmueble::where('idInmueble', $request->inmueble)
                                       ->where('id', '<>', $request->id)
                                       ->where('fechaHoraInicio', '<=', $request->fechaHoraInicio)
                                       ->where('fechaHoraFin','>=', $request->fechaHoraFin)
                                       ->get();


        if (sizeof($solapamientoFechas) != 0) {
          return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y Finalización se solapan con otra Reserva, por favor revise la misma');
        }


        //valido que el Inmueble exista
        $validarInmueble = Inmueble::where('id', $request->inmueble)->first();

        if (!isset($validarInmueble)) {
          return redirect()->back()->withInput()->with('validarInmueble', 'Error al seleccionar un Inmueble.');
        }


        //obtengo la persona correspondiente
        $persona = Persona::where('id', $request->idPersona)->first();

        //valido que la persona exista
        if (!isset($persona)) {
          return redirect()->back()->withInput()->with('validarPersonaExiste', 'Error al seleccionar la Persona.');
        }


        if ($request->numRecibo != null) {  //porque no es necesario comprobar cuando el alquiler no se ha pagado o se borra el numRecibo

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
            if (($alquilerInmueble->numRecibo == $request->numRecibo) && ($alquilerInmueble->id != $request->id)) {
              return redirect()->back()->withInput()->with('validarNumRecibo', 'Error, dicho Número de Recibo ha sido usado en un Alquiler de Inmueble.');
            }
          }

          foreach ($registros as $registro) {
            if ($registro->numRecibo == $request->numRecibo) {
              return redirect()->back()->withInput()->with('validarNumRecibo', 'Error, dicho Número de Recibo ha sido usado en otro Registro.');
            }
          }
        }


        //valido que el costo de la reserva no sea mayor al del costo total
        if ($request->costoReserva > $request->costoTotal) {
          return redirect()->back()->withInput()->with('validarMonto', 'Error, el costo de la Reserva no puede ser mayor al costo Total del alquiler.');
        }



        //almaceno los nuevos datos en la BD
        $reservaOriginal->fechaSolicitud = $request->fechaSol;
        $reservaOriginal->fechaHoraInicio = $request->fechaHoraInicio;
        $reservaOriginal->fechaHoraFin = $request->fechaHoraFin;
        $reservaOriginal->costoReserva = $request->costoReserva;
        $reservaOriginal->observacion = $request->observacion;
        $reservaOriginal->tieneServicioLimpieza = intval($request->servicioLimp);
        $reservaOriginal->cantAsistentes = intval($request->cantAsistentes);
        $reservaOriginal->tipoEvento = $request->tipoEvento;
        $reservaOriginal->costoTotal = $request->costoTotal;
        $reservaOriginal->tieneMusica = intval($request->musica);
        $reservaOriginal->tieneReglamento = intval($request->reglamento);
        $reservaOriginal->idInmueble = $request->inmueble;
        $reservaOriginal->idPersona = $persona->id;
        $reservaOriginal->idMedioDePago = $request->medioPago;
        $reservaOriginal->numRecibo = $request->numRecibo;

        $reservaOriginal->save();

        //redirijo al metodo que lleva a la vista individual de la reserva editada
        return redirect()->action('AlquilerInmuebleController@getShowId', $reservaOriginal->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //tomo la reserva de inmueble a eliminar segun el id pasado por parametro
        $reservaInmueble = ReservaInmueble::destroy($request->id);

        //redirijo al listado
        return redirect()->action('AlquilerInmuebleController@getShow');
    }
}
