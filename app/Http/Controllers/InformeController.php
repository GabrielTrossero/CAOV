<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\CuotaController;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Socio;
use App\Deporte;
use App\MovExtras;
use App\ReservaInmueble;
use App\ReservaMueble;
use App\ComprobanteCuota;
use PDF;

class InformeController extends Controller
{
  /**
   * Show options on Informes y Estadisticas.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      return view('informe.menu');
  }

  /**
   * Show a list of Socios Deudores.
   *
   * @return \Illuminate\Http\Response
   */
  public function getDeudores()
  {
    $cuotasNoPagadas = ComprobanteCuota:: selectRaw('idSocio, numSocio, DNI, apellido, nombres, count(*) as count')
                                          ->where('fechaPago', null)
                                          ->where('inhabilitada', false)
                                          ->join('socio','socio.id','=','comprobantecuota.idSocio')
                                          ->join('persona','persona.id','=','socio.idPersona')
                                          ->groupBy(DB::raw('idSocio, numSocio, DNI, apellido, nombres'))
                                          ->get();

    return view('informe.sociosDeudores', compact('cuotasNoPagadas'));
  }

  /**
   * Generates a pdf of Socios Deudores.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfDeudores()
  {
    $cuotasNoPagadas = ComprobanteCuota:: selectRaw('idSocio, numSocio, DNI, apellido, nombres, count(*) as count')
                                          ->where('fechaPago', null)
                                          ->where('inhabilitada', false)
                                          ->join('socio','socio.id','=','comprobantecuota.idSocio')
                                          ->join('persona','persona.id','=','socio.idPersona')
                                          ->groupBy(DB::raw('idSocio, numSocio, DNI, apellido, nombres'))
                                          ->get();

    $pdf = PDF::loadView('pdf.deudores', ['cuotasNoPagadas' => $cuotasNoPagadas]);

    return $pdf->download('deudores.pdf');
  }

  /**
   * Show detail of Socio Deudor.
   *
   * @return \Illuminate\Http\Response
   */
  public function getSocioDeudor($id)
  {
    //tomo el socio
    $socio = Socio::find($id);

    $cuotaController = new CuotaController;

    $socio->edad = $cuotaController->calculaEdad($socio);

    //tomo las cuotas que debe
    $cuotasNoPagadas = ComprobanteCuota::all()
                                         ->where('fechaPago', null)
                                         ->where('inhabilitada', false)
                                         ->where('idSocio', $id);
    

    return view('informe.socioDeudor', compact('socio', 'cuotasNoPagadas'));
  }

  /**
   * Generates a pdf of Socio Deudor.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfSocioDeudor(Request $request)
  {
    //tomo el socio
    $socio = Socio::find($request->id);

    $cuotaController = new CuotaController;

    $socio->edad = $cuotaController->calculaEdad($socio);

    //tomo las cuotas que debe
    $cuotasNoPagadas = ComprobanteCuota::all()
                                         ->where('fechaPago', null)
                                         ->where('inhabilitada', false)
                                         ->where('idSocio', $request->id);

    $pdf = PDF::loadView('pdf.socioDeudor', ['socio' => $socio, 'cuotasNoPagadas' => $cuotasNoPagadas]);

    return $pdf->download('socio-deudor.pdf');
  }

  /**
   * Show the Cantidad de Socios.
   *
   * @return \Illuminate\Http\Response
   */
  public function getCantidadSocios()
  {
    //tomo todos los socios
    $socios = Socio::all();

    //calculo la cantidad de socios
    $cantidadSocios = sizeof($socios);

    //retorno la vista con la cantidad de socios
    return view('informe.cantidadSocios', compact('cantidadSocios'));
  }

  /**
   * Generates a pdf of Cantidad de Socios.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfCantidadSocios()
  {
    //tomo todos los socios
    $socios = Socio::all();

    //calculo la cantidad de socios
    $cantidadSocios = sizeof($socios);

    $pdf = PDF::loadView('pdf.cantidadSocios', ['cantidadSocios' => $cantidadSocios]);

    return $pdf->download('cantidad-socios.pdf');
  }

  /**
   * Show a list with Cantidad de Socios por Deporte.
   *
   * @return \Illuminate\Http\Response
   */
  public function getCantidadSociosDeporte()
  {
    //tomo todos los deportes
    $deportes = Deporte::all();

    //calculco la cantidad de socios de cada deporte
    foreach ($deportes as $deporte) {
      $deporte->cantidadSocios = sizeof($deporte->socios);
    }

    //retorno la vista con la cantidad de socios por deporte
    return view('informe.cantidadSociosDeporte', compact('deportes'));
  }

  /**
   * Generates a pdf of Cantidad de Socios por Deporte.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfCantidadSociosDeporte()
  {
    //tomo todos los deportes
    $deportes = Deporte::all();

    //calculco la cantidad de socios de cada deporte
    foreach ($deportes as $deporte) {
      $deporte->cantidadSocios = sizeof($deporte->socios);
    }

    $pdf = PDF::loadView('pdf.cantidadSociosDeporte', ['deportes' => $deportes]);

    return $pdf->download('cantidad-socios-deporte.pdf');
  }

  /**
   * Show a menu of Ingresos y Egresos.
   *
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresos()
  {
    /*
      //tomo todos los movimientos extras
      $movExtras = MovExtras::all();

      //tomo los alquileres de inmueble pagados
      $alquileresInmueblePagos = ReservaInmueble::selectRaw('MONTH(fechaHoraInicio) as mes, YEAR(fechaHoraInicio) as anio, SUM(costoTotal) as total')
                                                  ->where('numRecibo','<>',null)
                                                  ->groupBy(DB::raw('mes, anio'))->get();

      //tomo los alquileres de mueble pagados
      $alquileresMueblePagos = ReservaMueble::selectRaw('MONTH(fechaHoraInicio) as mes, YEAR(fechaHoraInicio) as anio, SUM(costoTotal) as total')              
                                              ->where('numRecibo','<>',null)
                                              ->groupBy(DB::raw('mes, anio'))->get();

      //tomo los pagos de cuotas
      $cuotasPagadas = ComprobanteCuota::selectRaw("MONTH(comprobantecuota.fechaPago) as mes, YEAR(comprobantecuota.fechaPago) as anio, SUM(CASE WHEN comprobantecuota.tipo = 'a' THEN montocuota.monto - (montocuota.monto * (montocuota.dtoAnio / 100)) WHEN comprobantecuota.tipo = 's' THEN montocuota.monto -(montocuota.monto * (montocuota.dtoSemestre / 100)) WHEN comprobantecuota.tipo = 'm' THEN montocuota.monto END) as total")
                                         ->join('montocuota','montocuota.id','=','comprobantecuota.idMontoCuota')
                                         ->groupBy(DB::raw('mes, anio'))->get();

      //redirijo a la vistas con los datos de ingresos/egresos
      return view('informe.ingresosEgresos', compact('movExtras', 'alquileresInmueblePagos', 'alquileresMueblePagos', 'cuotasPagadas'));
    */
    return view('informe.ingresosEgresos');
  }

  /**
   * Devuelve la fecha de hoy en formato Y-m-d
   * 
   * @return Carbon\Carbon
   */
  public function fechaHoy()
  {
    return Carbon::now();
  }

  /**
   * Shows a list of Ingresos/Egresos diarios
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosDiarios()
  {
    //tomo los movimientos extra
    $movExtras = MovExtras::all();
    
    //filtro los movimientos extra que son distintos a la fecha de hoy
    $movExtras = $movExtras->filter(function($movExtra){
      $now = $this->fechaHoy()->format('Y-m-d');
      return $movExtra->fecha == $now;
    });

    //tomo los alquileres de inmuebles pagados
    $alquileresInmueblePagos = ReservaInmueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de inmuebles que son distintos a la fecha de hoy
    $alquileresInmueblePagos = $alquileresInmueblePagos->filter(function($alquilerInmueblePago){
      $now = $this->fechaHoy()->format('Y-m-d');
      return $alquilerInmueblePago->fechaSolicitud == $now;
    });

    //tomo los alquileres de muebles pagados
    $alquileresMueblePagos = ReservaMueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de muebles que son distintos a la fecha de hoy
    $alquileresMueblePagos = $alquileresMueblePagos->filter(function($alquilerMueblePago){
      $now = $this->fechaHoy()->format('Y-m-d');
      return $alquilerMueblePago->fechaSolicitud == $now;
    });

    //tomo los pagos de cuotas
    $cuotasPagadas = ComprobanteCuota::all()->where('fechaPago', '<>', null)->where('inhabilitada', false);
    
    //filtro los pagos de cuotas que son distintos a la fecha de hoy
    $cuotasPagadas = $cuotasPagadas->filter(function($cuotaPagada){
      $now = $this->fechaHoy()->format('Y-m-d');
      return $cuotaPagada->fechaPago == $now;
    });

    //calculo el monto de las cuotas pagadas
    foreach($cuotasPagadas as $cuotaPagada) {
      $cuotaController = new CuotaController;

      $interesPorIntegrantes = $cuotaController->montoInteresGrupoFamiliar($cuotaPagada);
      $interesMesesAtrasados = $cuotaController->montoInteresAtraso($cuotaPagada);
      $montoMensual = $cuotaPagada->montoCuota->montoMensual;

      $cuotaPagada->montoTotal = $montoMensual + $interesPorIntegrantes + $interesMesesAtrasados;
    }

    return view('informe.ingresosEgresos.ingresosEgresosDiarios', compact('movExtras', 
                                                                          'alquileresInmueblePagos',
                                                                          'alquileresMueblePagos',
                                                                          'cuotasPagadas'));
  }

  /**
   * Generates a pdf of Ingresos y Egresos Diarios.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfIngresosEgresosDiarios()
  {
    //tomo los movimientos extra
    $movExtras = MovExtras::all();
    
    //filtro los movimientos extra que son distintos a la fecha de hoy
    $movExtras = $movExtras->filter(function($movExtra){
      $now = $this->fechaHoy()->format('Y-m-d');
      return $movExtra->fecha == $now;
    });

    //tomo los alquileres de inmuebles pagados
    $alquileresInmueblePagos = ReservaInmueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de inmuebles que son distintos a la fecha de hoy
    $alquileresInmueblePagos = $alquileresInmueblePagos->filter(function($alquilerInmueblePago){
      $now = $this->fechaHoy()->format('Y-m-d');
      return $alquilerInmueblePago->fechaSolicitud == $now;
    });

    //tomo los alquileres de muebles pagados
    $alquileresMueblePagos = ReservaMueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de muebles que son distintos a la fecha de hoy
    $alquileresMueblePagos = $alquileresMueblePagos->filter(function($alquilerMueblePago){
      $now = $this->fechaHoy()->format('Y-m-d');
      return $alquilerMueblePago->fechaSolicitud == $now;
    });

    //tomo los pagos de cuotas
    $cuotasPagadas = ComprobanteCuota::all()->where('fechaPago', '<>', null)->where('inhabilitada', false);
    
    //filtro los pagos de cuotas que son distintos a la fecha de hoy
    $cuotasPagadas = $cuotasPagadas->filter(function($cuotaPagada){
      $now = $this->fechaHoy()->format('Y-m-d');
      return $cuotaPagada->fechaPago == $now;
    });

    //calculo el monto de las cuotas pagadas
    foreach($cuotasPagadas as $cuotaPagada) {
      $cuotaController = new CuotaController;

      $interesPorIntegrantes = $cuotaController->montoInteresGrupoFamiliar($cuotaPagada);
      $interesMesesAtrasados = $cuotaController->montoInteresAtraso($cuotaPagada);
      $montoMensual = $cuotaPagada->montoCuota->montoMensual;

      $cuotaPagada->montoTotal = $montoMensual + $interesPorIntegrantes + $interesMesesAtrasados;
    }

    $pdf = PDF::loadView('pdf.ingresosEgresosDiarios', ['movExtras' => $movExtras,
                                                        'alquileresInmueblePagos' => $alquileresInmueblePagos,
                                                        'alquileresMueblePagos' => $alquileresMueblePagos,
                                                        'cuotasPagadas' => $cuotasPagadas]);

    return $pdf->download('ingresos-egresos-diarios.pdf');
  }

  /**
   * Shows a list of Ingresos/Egresos Semanales
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosSemanales()
  {
    //tomo los movimientos extra
    $movExtras = MovExtras::all();
    
    //filtro los movimientos extra que son mayores a 7 dias
    $movExtras = $movExtras->filter(function($movExtra){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($movExtra->fecha);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 7;
    });

    //tomo los alquileres de inmuebles pagados
    $alquileresInmueblePagos = ReservaInmueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de inmuebles que son mayores a 7 dias
    $alquileresInmueblePagos = $alquileresInmueblePagos->filter(function($alquilerInmueblePago){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($alquilerInmueblePago->fechaSolicitud);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 7;
    });

    //tomo los alquileres de muebles pagados
    $alquileresMueblePagos = ReservaMueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de muebles que son mayores a 7 dias
    $alquileresMueblePagos = $alquileresMueblePagos->filter(function($alquilerMueblePago){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($alquilerMueblePago->fechaSolicitud);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 7;
    });

    //tomo los pagos de cuotas
    $cuotasPagadas = ComprobanteCuota::all()->where('fechaPago', '<>', null)->where('inhabilitada', false);
    
    //filtro los pagos de cuotas que son mayores a 7 dias
    $cuotasPagadas = $cuotasPagadas->filter(function($cuotaPagada){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($cuotaPagada->fechaPago);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 7;
    });

    //calculo el monto de las cuotas pagadas
    foreach($cuotasPagadas as $cuotaPagada) {
      $cuotaController = new CuotaController;

      $interesPorIntegrantes = $cuotaController->montoInteresGrupoFamiliar($cuotaPagada);
      $interesMesesAtrasados = $cuotaController->montoInteresAtraso($cuotaPagada);
      $montoMensual = $cuotaPagada->montoCuota->montoMensual;

      $cuotaPagada->montoTotal = $montoMensual + $interesPorIntegrantes + $interesMesesAtrasados;
    }

    return view('informe.ingresosEgresos.ingresosEgresosSemanales', compact('movExtras', 
                                                                          'alquileresInmueblePagos',
                                                                          'alquileresMueblePagos',
                                                                          'cuotasPagadas'));
  }

  /**
   * Generates a pdf of Ingresos y Egresos Semanales.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfIngresosEgresosSemanales()
  {
    //tomo los movimientos extra
    $movExtras = MovExtras::all();
    
    //filtro los movimientos extra que son mayores a 7 dias
    $movExtras = $movExtras->filter(function($movExtra){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($movExtra->fecha);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 7;
    });

    //tomo los alquileres de inmuebles pagados
    $alquileresInmueblePagos = ReservaInmueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de inmuebles que son mayores a 7 dias
    $alquileresInmueblePagos = $alquileresInmueblePagos->filter(function($alquilerInmueblePago){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($alquilerInmueblePago->fechaSolicitud);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 7;
    });

    //tomo los alquileres de muebles pagados
    $alquileresMueblePagos = ReservaMueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de muebles que son mayores a 7 dias
    $alquileresMueblePagos = $alquileresMueblePagos->filter(function($alquilerMueblePago){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($alquilerMueblePago->fechaSolicitud);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 7;
    });

    //tomo los pagos de cuotas
    $cuotasPagadas = ComprobanteCuota::all()->where('fechaPago', '<>', null)->where('inhabilitada', false);
    
    //filtro los pagos de cuotas que son mayores a 7 dias
    $cuotasPagadas = $cuotasPagadas->filter(function($cuotaPagada){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($cuotaPagada->fechaPago);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 7;
    });

    //calculo el monto de las cuotas pagadas
    foreach($cuotasPagadas as $cuotaPagada) {
      $cuotaController = new CuotaController;

      $interesPorIntegrantes = $cuotaController->montoInteresGrupoFamiliar($cuotaPagada);
      $interesMesesAtrasados = $cuotaController->montoInteresAtraso($cuotaPagada);
      $montoMensual = $cuotaPagada->montoCuota->montoMensual;

      $cuotaPagada->montoTotal = $montoMensual + $interesPorIntegrantes + $interesMesesAtrasados;
    }

    $pdf = PDF::loadView('pdf.ingresosEgresosSemanales', ['movExtras' => $movExtras,
                                                          'alquileresInmueblePagos' => $alquileresInmueblePagos,
                                                          'alquileresMueblePagos' => $alquileresMueblePagos,
                                                          'cuotasPagadas' => $cuotasPagadas]);

    return $pdf->download('ingresos-egresos-semanales.pdf');
  }

  /**
   * Shows a list of Ingresos/Egresos Mensuales
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosMensuales()
  {
    //tomo los movimientos extra
    $movExtras = MovExtras::all();
    
    //filtro los movimientos extra que son mayores a 30 dias
    $movExtras = $movExtras->filter(function($movExtra){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($movExtra->fecha);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 30;
    });

    //tomo los alquileres de inmuebles pagados
    $alquileresInmueblePagos = ReservaInmueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de inmuebles que son mayores a 30 dias
    $alquileresInmueblePagos = $alquileresInmueblePagos->filter(function($alquilerInmueblePago){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($alquilerInmueblePago->fechaSolicitud);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 30;
    });

    //tomo los alquileres de muebles pagados
    $alquileresMueblePagos = ReservaMueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de muebles que son mayores a 30 dias
    $alquileresMueblePagos = $alquileresMueblePagos->filter(function($alquilerMueblePago){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($alquilerMueblePago->fechaSolicitud);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 30;
    });

    //tomo los pagos de cuotas
    $cuotasPagadas = ComprobanteCuota::all()->where('fechaPago', '<>', null)->where('inhabilitada', false);
    
    //filtro los pagos de cuotas que son mayores a 30 dias
    $cuotasPagadas = $cuotasPagadas->filter(function($cuotaPagada){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($cuotaPagada->fechaPago);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 30;
    });

    //calculo el monto de las cuotas pagadas
    foreach($cuotasPagadas as $cuotaPagada) {
      $cuotaController = new CuotaController;

      $interesPorIntegrantes = $cuotaController->montoInteresGrupoFamiliar($cuotaPagada);
      $interesMesesAtrasados = $cuotaController->montoInteresAtraso($cuotaPagada);
      $montoMensual = $cuotaPagada->montoCuota->montoMensual;

      $cuotaPagada->montoTotal = $montoMensual + $interesPorIntegrantes + $interesMesesAtrasados;
    }

    return view('informe.ingresosEgresos.ingresosEgresosMensuales', compact('movExtras', 
                                                                            'alquileresInmueblePagos',
                                                                            'alquileresMueblePagos',
                                                                            'cuotasPagadas'));
  }

  /**
   * Generates a pdf of Ingresos y Egresos Mensuales.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfIngresosEgresosMensuales()
  {
    //tomo los movimientos extra
    $movExtras = MovExtras::all();
    
    //filtro los movimientos extra que son mayores a 30 dias
    $movExtras = $movExtras->filter(function($movExtra){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($movExtra->fecha);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 30;
    });

    //tomo los alquileres de inmuebles pagados
    $alquileresInmueblePagos = ReservaInmueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de inmuebles que son mayores a 30 dias
    $alquileresInmueblePagos = $alquileresInmueblePagos->filter(function($alquilerInmueblePago){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($alquilerInmueblePago->fechaSolicitud);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 30;
    });

    //tomo los alquileres de muebles pagados
    $alquileresMueblePagos = ReservaMueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de muebles que son mayores a 30 dias
    $alquileresMueblePagos = $alquileresMueblePagos->filter(function($alquilerMueblePago){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($alquilerMueblePago->fechaSolicitud);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 30;
    });

    //tomo los pagos de cuotas
    $cuotasPagadas = ComprobanteCuota::all()->where('fechaPago', '<>', null)->where('inhabilitada', false);
    
    //filtro los pagos de cuotas que son mayores a 30 dias
    $cuotasPagadas = $cuotasPagadas->filter(function($cuotaPagada){
      $now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($cuotaPagada->fechaPago);
      $diferenciaEnDias = $now->diffInDays($fechaFormateada);

      return $diferenciaEnDias <= 30;
    });

    //calculo el monto de las cuotas pagadas
    foreach($cuotasPagadas as $cuotaPagada) {
      $cuotaController = new CuotaController;

      $interesPorIntegrantes = $cuotaController->montoInteresGrupoFamiliar($cuotaPagada);
      $interesMesesAtrasados = $cuotaController->montoInteresAtraso($cuotaPagada);
      $montoMensual = $cuotaPagada->montoCuota->montoMensual;

      $cuotaPagada->montoTotal = $montoMensual + $interesPorIntegrantes + $interesMesesAtrasados;
    }

    $pdf = PDF::loadView('pdf.ingresosEgresosMensuales', ['movExtras' => $movExtras,
                                                          'alquileresInmueblePagos' => $alquileresInmueblePagos,
                                                          'alquileresMueblePagos' => $alquileresMueblePagos,
                                                          'cuotasPagadas' => $cuotasPagadas]);

    return $pdf->download('ingresos-egresos-mensuales.pdf');
  }

  /**
   * Show a list with Pagos.
   *
   * @return \Illuminate\Http\Response
   */
  public function getPagos()
  {
    //tomo los pagos de cuotas
    $cuotasPagadas = ComprobanteCuota::all()->where('fechaPago', '<>', null)->where('inhabilitada', false);
    
    foreach($cuotasPagadas as $cuotaPagada) {
      $cuotaController = new CuotaController;

      $interesPorIntegrantes = $cuotaController->montoInteresGrupoFamiliar($cuotaPagada);
      $interesMesesAtrasados = $cuotaController->montoInteresAtraso($cuotaPagada);
      $montoMensual = $cuotaPagada->montoCuota->montoMensual;

      $cuotaPagada->montoTotal = $montoMensual + $interesPorIntegrantes + $interesMesesAtrasados;
    }

    //tomo los alquileres de inmuebles
    $reservasInmueble = ReservaInmueble::all()->where('numRecibo', '<>', null);

    //tomo los alquileres de muebles
    $reservasMueble = ReservaMueble::all()->where('numRecibo', '<>', null);

    return view('informe.pagos', compact(['cuotasPagadas',
                                          'reservasInmueble',
                                          'reservasMueble'
                                          ])
                                          );
  }

  /**
   * Generates a pdf of Pagos.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfPagos()
  {
    //tomo los pagos de cuotas
    $cuotasPagadas = ComprobanteCuota::all()->where('fechaPago', '<>', null)->where('inhabilitada', false);
    
    foreach($cuotasPagadas as $cuotaPagada) {
      $cuotaController = new CuotaController;

      $interesPorIntegrantes = $cuotaController->montoInteresGrupoFamiliar($cuotaPagada);
      $interesMesesAtrasados = $cuotaController->montoInteresAtraso($cuotaPagada);
      $montoMensual = $cuotaPagada->montoCuota->montoMensual;

      $cuotaPagada->montoTotal = $montoMensual + $interesPorIntegrantes + $interesMesesAtrasados;
    }

    //tomo los alquileres de inmuebles
    $reservasInmueble = ReservaInmueble::all()->where('numRecibo', '<>', null);

    //tomo los alquileres de muebles
    $reservasMueble = ReservaMueble::all()->where('numRecibo', '<>', null);

    $pdf = PDF::loadView('pdf.pagos', ['cuotasPagadas' => $cuotasPagadas,
                                       'reservasInmueble' => $reservasInmueble,
                                       'reservasMueble' => $reservasMueble]);

    return $pdf->download('pagos.pdf');
  }
}
