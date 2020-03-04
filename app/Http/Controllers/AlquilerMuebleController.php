<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use App\ReservaMueble;
use App\ReservaInmueble;
use App\MovExtras;
use App\MedioDePago;
use App\Mueble;
use App\Persona;
use Carbon\Carbon;

class AlquilerMuebleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('alquilerMueble.menu');
    }

    /**
     * Devuelve información acerca de la disponibilidad de horarios en tal fecha
     *
     *
     */
    public function postDisponibilidad(){
      $muebleSeleccionado = Input::get('mueble');
      $alquileres = ReservaMueble::all()->where('idMueble', $muebleSeleccionado);
      $fecha = Carbon::parse(Input::get('fecha'))->format('Y-m-d');

      $mueble = Mueble::find($muebleSeleccionado);
      $stockRestante = $mueble->cantidad;

      $fechasReservadas = array();

      foreach($alquileres as $alquiler){
        $alquiler->soloFecha = Carbon::parse($alquiler->fechaHoraInicio)->format('Y-m-d');

        if ($alquiler->soloFecha == $fecha) {
          $fechasReservadas[] = array($alquiler->soloFecha, $alquiler->fechaHoraInicio, $alquiler->fechaHoraFin, $alquiler->cantidad);
          $stockRestante -= $alquiler->cantidad;
        }
      }

      return response()->json(['fechasReservadas' => $fechasReservadas,
                               'stockRestante' => $stockRestante]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //obtengo todos los muebles
        $muebles = Mueble::all();

        //obtnego todos los medios de pagos
        $mediosDePagos = MedioDePago::all();

        //tomo todas las personas para mostrarlas en el select
        $personas = Persona::all();

        //se los envio a la vista
        return view('alquilerMueble.agregar', compact(['muebles','mediosDePagos', 'personas']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //mensajes de error que se mostraran por pantalla
      $messages = [
        'idPersona.required' => 'Es necesario ingresar una Persona.',
        'fechaSolicitud.required' => 'Es necesario ingresar una Fecha.',
        'tipoMueble.required' => 'Seleccione un Mueble.',
        'cantMueble.required' => 'Es necesario ingresar una Cantidad.',
        'fechaHoraInicio.required' => 'Es necesario ingresar una Fecha y Hora de Inicio.',
        'fechaHoraFin.required' => 'Es necesario ingresar una Fecha y Hora de Finalización.',
        'costo.required' => 'Es necesario ingresar el Costo.',
        'medioPago.required' => 'Es necesario ingresar un Medio de Pago',
        'medioPago.in' => 'Seleccione un Medio de Pago válido.',
        'observacion.max' => 'La Observación no puede ser tan extensa'
      ];

      //valido los datos ingresados
      $validacion = Validator::make($request->all(), [
        'idPersona' => 'required',
        'fechaSolicitud' => 'required',
        'tipoMueble' => 'required',
        'cantMueble' => 'required',
        'fechaHoraInicio' => 'required',
        'fechaHoraFin' => 'required',
        'costo' => 'required',
        'medioPago' => 'required|in:1',
        'observacion' => 'max:100'
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


      $alquileresSolapados = new \Illuminate\Database\Eloquent\Collection; //colección donde voy a almacenar todas las reservas que interceptan con la que se quiere generar para finalmente verificar el stock

      //obtengo todas las reservas de dicho Mueble, donde la fechaHora de inicio esté entre la fechaHora de inicio y fin que ingresé
      $alquiler = ReservaMueble::where('idMueble', $request->tipoMueble)
                                     ->whereBetween('fechaHoraInicio', [$request->fechaHoraInicio, $request->fechaHoraFin])->get();

      $alquileresSolapados = $alquileresSolapados->merge($alquiler);  //lo almaceno en la colección "global"


      //obtengo todas las reservas de dicho Mueble, donde la fechaHora de fin esté entre la fechaHora de inicio y fin que ingresé
      $alquiler = ReservaMueble::where('idMueble', $request->tipoMueble)
                                     ->whereBetween('fechaHoraFin', [$request->fechaHoraInicio, $request->fechaHoraFin])->get();

      $alquileresSolapados = $alquileresSolapados->merge($alquiler);  //lo almaceno en la colección "global"


      //valido solapamiento entre fechas ingresadas y las fechas y horas de inicio y fin en la BD, del mueble a alquilar
      //(si alguna reserva está entre medio de las que ingresó)
      $alquiler = ReservaMueble::where('idMueble', $request->tipoMueble)
                                     ->where('id', '<>', $request->id)
                                     ->where('fechaHoraInicio', '<=', $request->fechaHoraInicio)
                                     ->where('fechaHoraFin','>=', $request->fechaHoraFin)
                                     ->get();

      $alquileresSolapados = $alquileresSolapados->merge($alquiler);  //lo almaceno en la colección "global"

      $cantidadStock = 0;
      foreach ($alquileresSolapados as $alquiler) { //sumo el stock que se debería tener en ese periodo
        $cantidadStock += $alquiler->cantidad;
      }

      $mueble = Mueble::where('id', $request->tipoMueble)->first();  //busco el mueble a alquilar

      if (($cantidadStock + $request->cantMueble) > $mueble->cantidad) { //si supera la cantidad del stock muestro error
        return redirect()->back()->withInput()->with('sinStock', 'No hay Stock suficiente para esa hora. Consulte disponibilidad más arriba.');
      }


      //valido que el Mueble exista
      $validarMueble = Mueble::where('id', $request->tipoMueble)->first();

      if (!isset($validarMueble)) {
        return redirect()->back()->withInput()->with('validarMueble', 'Error al seleccionar un Mueble.');
      }



      //alamceno el nuevo registro en la BD
      $reserva = new ReservaMueble;

      //almaceno la información
      $reserva->costoTotal = $request->costo;
      $reserva->fechaHoraInicio = $request->fechaHoraInicio;
      $reserva->fechaHoraFin = $request->fechaHoraFin;
      $reserva->fechaSolicitud = $request->fechaSolicitud;
      $reserva->cantidad = $request->cantMueble;
      $reserva->observacion = $request->observacion;
      $reserva->idMueble = $request->tipoMueble;
      $reserva->idPersona = $persona->id;
      $reserva->idMedioDePago = $request->medioPago;

      $reserva->save();

      //redirijo para mostrar la reserva ingresada
      return redirect()->action('AlquilerMuebleController@getShowId', $reserva->id);
    }

    /**
     * Display the resource list.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function getShow()
    {
        //almaceno todas las reservas y se las envío a la vista
        $reservas = ReservaMueble::all();

        return view('alquilerMueble.listado', compact('reservas'));
    }

    private function total($alquiler)
    {
        //retorna al socio con su edad
        return $alquiler->costoTotal;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShowId($id)
    {
        //busco la reserva solicitada y se la envío a la vista
        $reserva = ReservaMueble::find($id);

        //busco las reservas relacionadas a la anterior, exeptuando la anterior, para mostrarlas en el 2do cuadro de la vista
        $reservasRelacionadas = ReservaMueble::where('numRecibo', $reserva->numRecibo)
                                            ->where('id', '!=', $id)->get();

        //busco los contratos que tengan el mismo número de recibo para mostrarlo en el 3er cuadro de la vista
        $infoRecibo = ReservaMueble::where('numRecibo', $reserva->numRecibo)->get();

        //calculo el costo total del recibo
        $total = 0;
        foreach ($infoRecibo as $alquiler) {
          //llamo a la funcion total, pasandole cada alquiler
          $total = $total + $this->total($alquiler);
        }

        return view('alquilerMueble.individual', compact('reserva', 'reservasRelacionadas', 'infoRecibo', 'total'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //obtengo todos los muebles
        $muebles = Mueble::all();

        //obtnego todos los medios de pagos
        $mediosDePagos = MedioDePago::all();

        //obtengo la reserva a editar
        $reserva = ReservaMueble::find($id);

        //tomo todas las personas para mostrarlas en el select
        $personas = Persona::all();

        //se lo envío a la vista
        return view('alquilerMueble.editar', compact(['muebles','mediosDePagos','reserva', 'personas']));
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
        //mensajes de error que se mostraran por pantalla
        $messages = [
          'idPersona.required' => 'Es necesario ingresar una Persona.',
          'fechaSolicitud.required' => 'Es necesario ingresar una Fecha.',
          'tipoMueble.required' => 'Seleccione un Mueble.',
          'cantMueble.required' => 'Es necesario ingresar una Cantidad.',
          'fechaHoraInicio.required' => 'Es necesario ingresar una Fecha y Hora de Inicio.',
          'fechaHoraFin.required' => 'Es necesario ingresar una Fecha y Hora de Finalización.',
          'costo.required' => 'Es necesario ingresar el Costo.',
          'medioPago.required' => 'Es necesario ingresar un Medio de Pago',
          'observacion.max' => 'La Observación no puede ser tan extensa'
        ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(), [
          'idPersona' => 'required',
          'fechaSolicitud' => 'required',
          'tipoMueble' => 'required',
          'cantMueble' => 'required',
          'fechaHoraInicio' => 'required',
          'fechaHoraFin' => 'required',
          'costo' => 'required',
          'medioPago' => 'required',
          'observacion' => 'max:100',
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withInput()->withErrors($validacion->errors());
        }


        $alquileresSolapados = new \Illuminate\Database\Eloquent\Collection; //colección donde voy a almacenar todas las reservas que interceptan con la que se quiere generar para finalmente verificar el stock

        //obtengo todas las reservas de dicho Mueble, donde la fechaHora de inicio esté entre la fechaHora de inicio y fin que ingresé
        $alquiler = ReservaMueble::where('idMueble', $request->tipoMueble)
                                       ->where('id', '<>', $request->id)
                                       ->whereBetween('fechaHoraInicio', [$request->fechaHoraInicio, $request->fechaHoraFin])->get();

        $alquileresSolapados = $alquileresSolapados->merge($alquiler);  //lo almaceno en la colección "global"


        //obtengo todas las reservas de dicho Mueble, donde la fechaHora de fin esté entre la fechaHora de inicio y fin que ingresé
        $alquiler = ReservaMueble::where('idMueble', $request->tipoMueble)
                                       ->where('id', '<>', $request->id)
                                       ->whereBetween('fechaHoraFin', [$request->fechaHoraInicio, $request->fechaHoraFin])->get();

        $alquileresSolapados = $alquileresSolapados->merge($alquiler);  //lo almaceno en la colección "global"


        //valido solapamiento entre fechas ingresadas y las fechas y horas de inicio y fin en la BD, del mueble a alquilar
        //(si alguna reserva está entre medio de las que ingresó)
        $alquiler = ReservaMueble::where('idMueble', $request->tipoMueble)
                                       ->where('id', '<>', $request->id)
                                       ->where('fechaHoraInicio', '<=', $request->fechaHoraInicio)
                                       ->where('fechaHoraFin','>=', $request->fechaHoraFin)
                                       ->get();

        $alquileresSolapados = $alquileresSolapados->merge($alquiler);  //lo almaceno en la colección "global"

        $cantidadStock = 0;
        foreach ($alquileresSolapados as $alquiler) { //sumo el stock que se debería tener en ese periodo
          $cantidadStock += $alquiler->cantidad;
        }

        $mueble = Mueble::where('id', $request->tipoMueble)->first();  //busco el mueble a alquilar

        if (($cantidadStock + $request->cantMueble) > $mueble->cantidad) { //si supera la cantidad del stock muestro error
          return redirect()->back()->withInput()->with('sinStock', 'No hay Stock suficiente para esa hora. Consulte disponibilidad más arriba.');
        }


        //valido que el Mueble exista
        $validarMueble = Mueble::where('id', $request->tipoMueble)->first();

        if (!isset($validarMueble)) {
          return redirect()->back()->withInput()->with('validarMueble', 'Error al seleccionar un Mueble.');
        }


        //obtengo la persona correspondiente
        $persona = Persona::where('id', $request->idPersona)->first();

        //valido que la persona exista
        if (!isset($persona)) {
          return redirect()->back()->withInput()->with('validarPersonaExiste', 'Error al seleccionar la Persona.');
        }


        if ($request->numRecibo != null) {  //porque no es necesario comprobar cuando el alquiler no se ha pagado o se borra el numRecibo

          //compruebo que el numRecibo no se repita en AlquilerInmueble y en Registros, y en AqluilerMueble compruebo que no haya otro con el mismo numRecibo y ademas con el mismo tipo de idMueble (porque en los alquileres de muebles si se puede repetir el numRecibo)
          $alquileresMueble = ReservaMueble::where('numRecibo', $request->numRecibo)->get();
          $alquileresInmueble = ReservaInmueble::all();
          $registros = MovExtras::all();

          foreach ($alquileresMueble as $alquilerMueble) {
            if (($alquilerMueble->mueble->id == $request->tipoMueble) && ($alquilerMueble->id != $request->id)) {
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
        }


        //actualizo dicho registro
        ReservaMueble::where('id', $request->id)
              ->update([
                'costoTotal' => $request->costo,
                'fechaHoraInicio' => $request->fechaHoraInicio,
                'fechaHoraFin' => $request->fechaHoraFin,
                'fechaSolicitud' => $request->fechaSolicitud,
                'cantidad' => $request->cantMueble,
                'observacion' => $request->observacion,
                'idMueble' => $request->tipoMueble,
                'idPersona' => $persona->id,
                'idMedioDePago' => $request->medioPago,
                'numRecibo' => $request->numRecibo
              ]);

        //redirijo a la vista individual
        return redirect()->action('AlquilerMuebleController@getShowId', $request->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //elimino el registro con tal id
        $reserva = ReservaMueble::destroy($request->id);

        //redirijo al listado
        return redirect()->action('AlquilerMuebleController@getShow');
    }
}
