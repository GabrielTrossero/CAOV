<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\ReservaMueble;
use App\MedioDePago;
use App\Mueble;
use App\Persona;

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

        //se los envio a la vista
        return view('alquilerMueble.agregar', compact(['muebles','mediosDePagos']));
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
        'DNI.required' => 'Es necesario ingresar un DNI válido.',
        'DNI.exists' => 'Error, ingrese el DNI de una Persona cargada en el sistema.',
        'DNI.min' => 'Es necesario ingresar un DNI válido.',
        'DNI.max' => 'Es necesario ingresar un DNI válido.',
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
        'DNI' => [
          'required',
          'min:8',
          'max:8',
          //hace select count(*) from persona where DNI = $request->DNI
          //para verificar que exista dicha persona
          Rule::exists('persona')
        ],
        'fechaSolicitud' => 'required',
        'tipoMueble' => 'required',
        'cantMueble' => 'required',
        'fechaHoraInicio' => 'required',
        'fechaHoraFin' => 'required',
        'costo' => 'required',
        'medioPago' => 'required',
        'observacion' => 'max:100'
      ], $messages);

      //si la validacion falla vuelvo hacia atras con los errores
      if($validacion->fails()){
        return redirect()->back()->withInput()->withErrors($validacion->errors());
      }

      //valido si la fecha y hora de finalizacion es menor a la de inicio
      if ($request->fechaHoraInicio >= $request->fechaHoraFin) {
        return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y Finalización son erróneas, por favor revise las mismas');
      }

      //valido solapamiento entre fechas ingresadas y las fechas y horas de inicio en la BD, del inmueble a alquilar.
      //Por lo tanto obtengo todas las reservas de dicho Inmueble, donde la fechaHora de inicio esté entre la fechaHora de inicio y fin que ingresé
      $solapamientoFechas = ReservaMueble::where('idMueble', $request->tipoMueble)
                                     ->whereBetween('fechaHoraInicio', [$request->fechaHoraInicio, $request->fechaHoraFin])->get();

      if (sizeof($solapamientoFechas) != 0) {
        return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y Finalización se solapan con otra Reserva, por favor revise la misma');
      }

      //valido solapamiento entre fechas ingresadas y las fechas y horas de fin en la BD, del inmueble a alquilar
      //Por lo tanto obtengo todas las reservas de dicho Inmueble, donde la fechaHora de fin esté entre la fechaHora de inicio y fin que ingresé
      $solapamientoFechas = ReservaMueble::where('idMueble', $request->tipoMueble)
                                     ->whereBetween('fechaHoraFin', [$request->fechaHoraInicio, $request->fechaHoraFin])->get();

      if (sizeof($solapamientoFechas) != 0) {
        return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y Finalización se solapan con otra Reserva, por favor revise la misma');
      }

      //valido solapamiento entre fechas ingresadas y las fechas y horas de inicio y fin en la BD, del inmueble a alquilar
      //(si alguna reserva está entre medio de las que ingresó)
      $solapamientoFechas = ReservaMueble::where('idMueble', $request->tipoMueble)
                                     ->where('id', '<>', $request->id)
                                     ->where('fechaHoraInicio', '<=', $request->fechaHoraInicio)
                                     ->where('fechaHoraFin','>=', $request->fechaHoraFin)
                                     ->get();


      if (sizeof($solapamientoFechas) != 0) {
        return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y Finalización se solapan con otra Reserva, por favor revise la misma');
      }

      //obtengo la persona correspondiente al DNI ingresado
      $persona = Persona::where('DNI', $request->DNI)->first();

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

        //se lo envío a la vista
        return view('alquilerMueble.editar', compact(['muebles','mediosDePagos','reserva']));
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
          'DNI.required' => 'Es necesario ingresar un DNI válido.',
          'DNI.exists' => 'Error, ingrese el DNI de una Persona cargada en el sistema.',
          'DNI.min' => 'Es necesario ingresar un DNI válido.',
          'DNI.max' => 'Es necesario ingresar un DNI válido.',
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
          'DNI' => [
            'required',
            'min:8',
            'max:8',
            //hace select count(*) from persona where DNI = $request->DNI
            //para verificar que exista dicha persona
            Rule::exists('persona')
          ],
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

        //tomo la reserva del inmueble
        $reservaOriginal = ReservaMueble::find($request->id);

        //valido si la fecha y hora de finalizacion es menor a la de inicio
        if ($request->fechaHoraInicio >= $request->fechaHoraFin) {
          return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y  Finalización son erróneas, por favor revise las mismas');
        }

        //valido solapamiento entre fechas ingresadas y las fechas y horas de inicio en la BD, del inmueble a alquilar
        //Por lo tanto obtengo todas las reservas de dicho Inmueble (omitiendo la que estoy actualizando), donde la fechaHora de inicio esté entre la fechaHora de inicio y fin que ingresé
        $solapamientoFechas = ReservaMueble::where('idMueble', $request->tipoMueble)
                                       ->where('id', '<>', $request->id)
                                       ->whereBetween('fechaHoraInicio', [$request->fechaHoraInicio, $request->fechaHoraFin])->get();

        if (sizeof($solapamientoFechas) != 0) {
          return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y Finalización se solapan con otra Reserva, por favor revise la misma');
        }

        //valido solapamiento entre fechas ingresadas y las fechas y horas de fin en la BD, del inmueble a alquilar
        //Por lo tanto obtengo todas las reservas de dicho Inmueble(omitiendo la que estoy actualizando), donde la fechaHora de fin esté entre la fechaHora de inicio y fin que ingresé
        $solapamientoFechas = ReservaMueble::where('idMueble', $request->tipoMueble)
                                       ->where('id', '<>', $request->id)
                                       ->whereBetween('fechaHoraFin', [$request->fechaHoraInicio, $request->fechaHoraFin])->get();

        if (sizeof($solapamientoFechas) != 0) {
          return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y Finalización se solapan con otra Reserva, por favor revise la misma');
        }

        //valido solapamiento entre fechas ingresadas y las fechas y horas de inicio y fin en la BD, del inmueble a alquilar
        //(si alguna reserva está entre medio de las que ingresó)
        $solapamientoFechas = ReservaMueble::where('idMueble', $request->tipoMueble)
                                       ->where('id', '<>', $request->id)
                                       ->where('fechaHoraInicio', '<=', $request->fechaHoraInicio)
                                       ->where('fechaHoraFin','>=', $request->fechaHoraFin)
                                       ->get();


        if (sizeof($solapamientoFechas) != 0) {
          return redirect()->back()->withInput()->with('solapamientoFechas', 'La Fecha y Hora de Inicio y Finalización se solapan con otra Reserva, por favor revise la misma');
        }

        //obtengo la persona correspondiente al DNI ingresado
        $persona = Persona::where('DNI', $request->DNI)->first();

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
