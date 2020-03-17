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
   * Calcula el monto total de una Cuota pagada
   * 
   * @param App\ComprobanteCuota $cuotaPagada
   * 
   * @return App\ComprobanteCuota
   */
  public function calculaMontoCuotaPagada($cuotaPagada)
  {
    $cuotaController = new CuotaController;

    $interesPorIntegrantes = $cuotaController->montoInteresGrupoFamiliar($cuotaPagada);
    $interesMesesAtrasados = $cuotaController->montoInteresAtraso($cuotaPagada);
    $montoMensual = $cuotaPagada->montoCuota->montoMensual;

    $cuotaPagada->montoTotal = $montoMensual + $interesPorIntegrantes + $interesMesesAtrasados;

    return $cuotaPagada;
  }

  /**
   * Muestra el listado general de los Ingresos y Egresos Diarios con su Total
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosDiariosGeneral() 
  {
    // Tomo los Registros (movimientos extra) diarios con su total
    $movExtras = MovExtras::select(DB::raw('fecha, sum(monto) as total, tipo'))
                            ->groupBy('fecha', 'tipo')
                            ->get();

    // Tomo las reservas de Inmuebles diarias con su total
    $reservasInmueble = ReservaInmueble::select(DB::raw("fechaSolicitud, sum(costoTotal) as total, 'Ingreso' as tipo"))
                             ->where('numRecibo', '<>', null)
                             ->groupBy('fechaSolicitud')
                             ->get();

    // Tomo las reservas de Muebles diarias con su total
    $reservasMueble = ReservaMueble::select(DB::raw("fechaSolicitud, sum(costoTotal) as total, 'Ingreso' as tipo"))
                             ->where('numRecibo', '<>', null)
                             ->groupBy('fechaSolicitud')
                             ->get();

    // Tomo las Cuotas pagadas diarias 
    $cuotasPagadas = ComprobanteCuota::all()
                                       ->where('fechaPago', '<>', null)
                                       ->where('inhabilitada', false);

    // Calculo el Monto Total de cada Cuota Pagada
    foreach ($cuotasPagadas as $cuotaPagada) {
      $cuotaPagada = $this->calculaMontoCuotaPagada($cuotaPagada);
    }

    // Acumulo los Montos de las Cuotas Pagadas en un array asociativo con KEY fecha
    $totales = array();

    // Inicializo los valores del array en 0 (cero)
    foreach ($movExtras as $movExtra) {
      $totales[$movExtra->fecha] = 0;
    }

    foreach ($reservasInmueble as $reservaInmueble) {
      $totales[$reservaInmueble->fechaSolicitud] = 0;
    }

    foreach ($reservasMueble as $reservaMueble) {
      $totales[$reservaMueble->fechaSolicitud] = 0;
    }

    foreach ($cuotasPagadas as $cuotaPagada) {
      $totales[$cuotaPagada->fechaPago] = 0;
    }

    // Acumulo los totales
    foreach ($movExtras as $movExtra) {
      if($movExtra->tipo == 1) {
        $totales[$movExtra->fecha] += $movExtra->total;
      } elseif ($movExtra->tipo == 2) {
        $totales[$movExtra->fecha] -= $movExtra->total;
      }
    }

    foreach ($reservasInmueble as $reservaInmueble) {
      $totales[$reservaInmueble->fechaSolicitud] += $reservaInmueble->total;
    }

    foreach ($reservasMueble as $reservaMueble) {
      $totales[$reservaMueble->fechaSolicitud] += $reservaMueble->total;
    }

    foreach ($cuotasPagadas as $cuotaPagada) {
      $totales[$cuotaPagada->fechaPago] += $cuotaPagada->montoTotal;
    }

    return view('informe.ingresosEgresos.ingresosEgresosDiariosGenerales', compact('totales'));
  }

  /**
   * Shows a list of Ingresos/Egresos diarios
   * 
   * @param string $fecha
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosDiarios($fecha)
  {
    //tomo los movimientos extra
    $movExtras = MovExtras::all();
    
    //filtro los movimientos extra que son distintos a la fecha de hoy
    $movExtras = $movExtras->filter(function($movExtra) use ($fecha) {
      //$now = $this->fechaHoy()->format('Y-m-d');
      return $movExtra->fecha == $fecha;
    });

    //tomo los alquileres de inmuebles pagados
    $alquileresInmueblePagos = ReservaInmueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de inmuebles que son distintos a la fecha de hoy
    $alquileresInmueblePagos = $alquileresInmueblePagos->filter(function($alquilerInmueblePago) use ($fecha) {
      //$now = $this->fechaHoy()->format('Y-m-d');
      return $alquilerInmueblePago->fechaSolicitud == $fecha;
    });

    //tomo los alquileres de muebles pagados
    $alquileresMueblePagos = ReservaMueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de muebles que son distintos a la fecha de hoy
    $alquileresMueblePagos = $alquileresMueblePagos->filter(function($alquilerMueblePago) use ($fecha) {
      //$now = $this->fechaHoy()->format('Y-m-d');
      return $alquilerMueblePago->fechaSolicitud == $fecha;
    });

    //tomo los pagos de cuotas
    $cuotasPagadas = ComprobanteCuota::all()->where('fechaPago', '<>', null)->where('inhabilitada', false);
    
    //filtro los pagos de cuotas que son distintos a la fecha de hoy
    $cuotasPagadas = $cuotasPagadas->filter(function($cuotaPagada) use ($fecha) {
      //$now = $this->fechaHoy()->format('Y-m-d');
      return $cuotaPagada->fechaPago == $fecha;
    });

    //calculo el monto de las cuotas pagadas
    foreach($cuotasPagadas as $cuotaPagada) {
      $cuotaPagada = $this->calculaMontoCuotaPagada($cuotaPagada);
    }

    return view('informe.ingresosEgresos.ingresosEgresosDiarios', compact('movExtras', 
                                                                          'alquileresInmueblePagos',
                                                                          'alquileresMueblePagos',
                                                                          'cuotasPagadas',
                                                                          'fecha'));
  }

  /**
   * Generates a pdf of Ingresos y Egresos Diarios.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfIngresosEgresosDiarios()
  {
    // Tomo los Registros (movimientos extra) diarios con su total
    $movExtras = MovExtras::select(DB::raw('fecha, sum(monto) as total, tipo'))
                            ->groupBy('fecha', 'tipo')
                            ->get();

    // Tomo las reservas de Inmuebles diarias con su total
    $reservasInmueble = ReservaInmueble::select(DB::raw("fechaSolicitud, sum(costoTotal) as total, 'Ingreso' as tipo"))
                             ->where('numRecibo', '<>', null)
                             ->groupBy('fechaSolicitud')
                             ->get();

    // Tomo las reservas de Muebles diarias con su total
    $reservasMueble = ReservaMueble::select(DB::raw("fechaSolicitud, sum(costoTotal) as total, 'Ingreso' as tipo"))
                             ->where('numRecibo', '<>', null)
                             ->groupBy('fechaSolicitud')
                             ->get();

    // Tomo las Cuotas pagadas diarias 
    $cuotasPagadas = ComprobanteCuota::all()
                                       ->where('fechaPago', '<>', null)
                                       ->where('inhabilitada', false);

    // Calculo el Monto Total de cada Cuota Pagada
    foreach ($cuotasPagadas as $cuotaPagada) {
      $cuotaPagada = $this->calculaMontoCuotaPagada($cuotaPagada);
    }

    // Acumulo los Montos de las Cuotas Pagadas en un array asociativo con KEY fecha
    $totales = array();

    // Inicializo los valores del array en 0 (cero)
    foreach ($movExtras as $movExtra) {
      $totales[$movExtra->fecha] = 0;
    }

    foreach ($reservasInmueble as $reservaInmueble) {
      $totales[$reservaInmueble->fechaSolicitud] = 0;
    }

    foreach ($reservasMueble as $reservaMueble) {
      $totales[$reservaMueble->fechaSolicitud] = 0;
    }

    foreach ($cuotasPagadas as $cuotaPagada) {
      $totales[$cuotaPagada->fechaPago] = 0;
    }

    // Acumulo los totales
    foreach ($movExtras as $movExtra) {
      if($movExtra->tipo == 1) {
        $totales[$movExtra->fecha] += $movExtra->total;
      } elseif ($movExtra->tipo == 2) {
        $totales[$movExtra->fecha] -= $movExtra->total;
      }
    }

    foreach ($reservasInmueble as $reservaInmueble) {
      $totales[$reservaInmueble->fechaSolicitud] += $reservaInmueble->total;
    }

    foreach ($reservasMueble as $reservaMueble) {
      $totales[$reservaMueble->fechaSolicitud] += $reservaMueble->total;
    }

    foreach ($cuotasPagadas as $cuotaPagada) {
      $totales[$cuotaPagada->fechaPago] += $cuotaPagada->montoTotal;
    }

    $pdf = PDF::loadView('pdf.ingresosEgresosDiarios', ['totales' => $totales]);

    return $pdf->download('ingresos-egresos-diarios.pdf');
  }

  /**
   * Muestra el listado general de los Ingresos y Egresos Semanales con su Total
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosSemanalesGeneral() 
  {
    // Tomo los Registros (movimientos extra) diarios con su total
    $movExtras = MovExtras::select(DB::raw('fecha, sum(monto) as total, tipo'))
                            ->groupBy('fecha', 'tipo')
                            ->get();

    // Tomo las reservas de Inmuebles diarias con su total
    $reservasInmueble = ReservaInmueble::select(DB::raw("fechaSolicitud, sum(costoTotal) as total, 'Ingreso' as tipo"))
                             ->where('numRecibo', '<>', null)
                             ->groupBy('fechaSolicitud')
                             ->get();

    // Tomo las reservas de Muebles diarias con su total
    $reservasMueble = ReservaMueble::select(DB::raw("fechaSolicitud, sum(costoTotal) as total, 'Ingreso' as tipo"))
                             ->where('numRecibo', '<>', null)
                             ->groupBy('fechaSolicitud')
                             ->get();

    // Tomo las Cuotas pagadas diarias 
    $cuotasPagadas = ComprobanteCuota::all()
                                       ->where('fechaPago', '<>', null)
                                       ->where('inhabilitada', false);

    // Calculo el Monto Total de cada Cuota Pagada
    foreach ($cuotasPagadas as $cuotaPagada) {
      $cuotaPagada = $this->calculaMontoCuotaPagada($cuotaPagada);
    }

    // Acumulo los Montos de las Cuotas Pagadas en un array asociativo con KEY fecha
    $totales = array();

    // Inicializo los valores del array en 0 (cero)
    foreach ($movExtras as $movExtra) {
      $fecha = Carbon::parse($movExtra->fecha);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      $totales[$anio." - ".$semana] = array("total" => 0, "semana" => $semana, "anio" => $anio);
    }

    foreach ($reservasInmueble as $reservaInmueble) {
      $fecha = Carbon::parse($reservaInmueble->fechaSolicitud);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      $totales[$anio." - ".$semana] = array("total" => 0, "semana" => $semana, "anio" => $anio);
    }

    foreach ($reservasMueble as $reservaMueble) {
      $fecha = Carbon::parse($reservaMueble->fechaSolicitud);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      $totales[$anio." - ".$semana] = array("total" => 0, "semana" => $semana, "anio" => $anio);
    }

    foreach ($cuotasPagadas as $cuotaPagada) {
      $fecha = Carbon::parse($cuotaPagada->fechaPago);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      $totales[$anio." - ".$semana] = array("total" => 0, "semana" => $semana, "anio" => $anio);
    }

    // Acumulo los totales
    foreach ($movExtras as $movExtra) {
      $fecha = Carbon::parse($movExtra->fecha);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      if($movExtra->tipo == 1) {
        $totales[$anio." - ".$semana]["total"] += $movExtra->total;
      } elseif ($movExtra->tipo == 2) {
        $totales[$anio." - ".$semana]["total"] -= $movExtra->total;
      }
    }

    foreach ($reservasInmueble as $reservaInmueble) {
      $fecha = Carbon::parse($reservaInmueble->fechaSolicitud);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      $totales[$anio." - ".$semana]["total"] += $reservaInmueble->total;
    }

    foreach ($reservasMueble as $reservaMueble) {
      $fecha = Carbon::parse($reservaMueble->fechaSolicitud);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      $totales[$anio." - ".$semana]["total"] += $reservaMueble->total;
    }

    foreach ($cuotasPagadas as $cuotaPagada) {
      $fecha = Carbon::parse($cuotaPagada->fechaPago);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      $totales[$anio." - ".$semana]["total"] += $cuotaPagada->montoTotal;
    }

    return view('informe.ingresosEgresos.ingresosEgresosSemanalesGenerales', compact('totales'));
  }
  /**
   * Shows a list of Ingresos/Egresos Semanales
   * 
   * @param string $semana
   * @param string $anio
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosSemanales($semana, $anio)
  {
    $semana = intval($semana);
    $anio = intval($anio);

    //tomo los movimientos extra
    $movExtras = MovExtras::all();
    
    //filtro los movimientos extra que son mayores a 7 dias
    $movExtras = $movExtras->filter(function($movExtra) use ($semana, $anio) {
      //$now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($movExtra->fecha);
      $semanaConsulta = $fechaFormateada->weekOfYear;
      $anioConsulta = $fechaFormateada->year;

      return (($semanaConsulta == $semana) && ($anioConsulta == $anio));
    });

    //tomo los alquileres de inmuebles pagados
    $alquileresInmueblePagos = ReservaInmueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de inmuebles que son mayores a 7 dias
    $alquileresInmueblePagos = $alquileresInmueblePagos->filter(function($alquilerInmueblePago) use ($semana, $anio) {
      //$now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($alquilerInmueblePago->fechaSolicitud);
      $semanaConsulta = $fechaFormateada->weekOfYear;
      $anioConsulta = $fechaFormateada->year;

      return (($semanaConsulta == $semana) && ($anioConsulta == $anio));
    });

    //tomo los alquileres de muebles pagados
    $alquileresMueblePagos = ReservaMueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de muebles que son mayores a 7 dias
    $alquileresMueblePagos = $alquileresMueblePagos->filter(function($alquilerMueblePago) use ($semana, $anio) {
      //$now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($alquilerMueblePago->fechaSolicitud);
      $semanaConsulta = $fechaFormateada->weekOfYear;
      $anioConsulta = $fechaFormateada->year;

      return (($semanaConsulta == $semana) && ($anioConsulta == $anio));
    });

    //tomo los pagos de cuotas
    $cuotasPagadas = ComprobanteCuota::all()->where('fechaPago', '<>', null)->where('inhabilitada', false);
    
    //filtro los pagos de cuotas que son mayores a 7 dias
    $cuotasPagadas = $cuotasPagadas->filter(function($cuotaPagada) use ($semana, $anio) {
      //$now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($cuotaPagada->fechaPago);
      $semanaConsulta = $fechaFormateada->weekOfYear;
      $anioConsulta = $fechaFormateada->year;

      return (($semanaConsulta == $semana) && ($anioConsulta == $anio));
    });

    //calculo el monto de las cuotas pagadas
    foreach($cuotasPagadas as $cuotaPagada) {
      $cuotaPagada = $this->calculaMontoCuotaPagada($cuotaPagada);
    }

    $semanaAnio = $semana." - ".$anio;

    return view('informe.ingresosEgresos.ingresosEgresosSemanales', compact('movExtras', 
                                                                            'alquileresInmueblePagos',
                                                                            'alquileresMueblePagos',
                                                                            'cuotasPagadas',
                                                                            'semanaAnio'));
  }

  /**
   * Generates a pdf of Ingresos y Egresos Semanales.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfIngresosEgresosSemanales()
  {
    // Tomo los Registros (movimientos extra) diarios con su total
    $movExtras = MovExtras::select(DB::raw('fecha, sum(monto) as total, tipo'))
                            ->groupBy('fecha', 'tipo')
                            ->get();

    // Tomo las reservas de Inmuebles diarias con su total
    $reservasInmueble = ReservaInmueble::select(DB::raw("fechaSolicitud, sum(costoTotal) as total, 'Ingreso' as tipo"))
                             ->where('numRecibo', '<>', null)
                             ->groupBy('fechaSolicitud')
                             ->get();

    // Tomo las reservas de Muebles diarias con su total
    $reservasMueble = ReservaMueble::select(DB::raw("fechaSolicitud, sum(costoTotal) as total, 'Ingreso' as tipo"))
                             ->where('numRecibo', '<>', null)
                             ->groupBy('fechaSolicitud')
                             ->get();

    // Tomo las Cuotas pagadas diarias 
    $cuotasPagadas = ComprobanteCuota::all()
                                       ->where('fechaPago', '<>', null)
                                       ->where('inhabilitada', false);

    // Calculo el Monto Total de cada Cuota Pagada
    foreach ($cuotasPagadas as $cuotaPagada) {
      $cuotaPagada = $this->calculaMontoCuotaPagada($cuotaPagada);
    }

    // Acumulo los Montos de las Cuotas Pagadas en un array asociativo con KEY fecha
    $totales = array();

    // Inicializo los valores del array en 0 (cero)
    foreach ($movExtras as $movExtra) {
      $fecha = Carbon::parse($movExtra->fecha);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      $totales[$anio." - ".$semana] = array("total" => 0, "semana" => $semana, "anio" => $anio);
    }

    foreach ($reservasInmueble as $reservaInmueble) {
      $fecha = Carbon::parse($reservaInmueble->fechaSolicitud);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      $totales[$anio." - ".$semana] = array("total" => 0, "semana" => $semana, "anio" => $anio);
    }

    foreach ($reservasMueble as $reservaMueble) {
      $fecha = Carbon::parse($reservaMueble->fechaSolicitud);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      $totales[$anio." - ".$semana] = array("total" => 0, "semana" => $semana, "anio" => $anio);
    }

    foreach ($cuotasPagadas as $cuotaPagada) {
      $fecha = Carbon::parse($cuotaPagada->fechaPago);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      $totales[$anio." - ".$semana] = array("total" => 0, "semana" => $semana, "anio" => $anio);
    }

    // Acumulo los totales
    foreach ($movExtras as $movExtra) {
      $fecha = Carbon::parse($movExtra->fecha);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      if($movExtra->tipo == 1) {
        $totales[$anio." - ".$semana]["total"] += $movExtra->total;
      } elseif ($movExtra->tipo == 2) {
        $totales[$anio." - ".$semana]["total"] -= $movExtra->total;
      }
    }

    foreach ($reservasInmueble as $reservaInmueble) {
      $fecha = Carbon::parse($reservaInmueble->fechaSolicitud);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      $totales[$anio." - ".$semana]["total"] += $reservaInmueble->total;
    }

    foreach ($reservasMueble as $reservaMueble) {
      $fecha = Carbon::parse($reservaMueble->fechaSolicitud);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      $totales[$anio." - ".$semana]["total"] += $reservaMueble->total;
    }

    foreach ($cuotasPagadas as $cuotaPagada) {
      $fecha = Carbon::parse($cuotaPagada->fechaPago);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      $totales[$anio." - ".$semana]["total"] += $cuotaPagada->montoTotal;
    }

    // Ordeno por semana/anio descendiente
    krsort($totales);

    $pdf = PDF::loadView('pdf.ingresosEgresosSemanales', ['totales' => $totales]);

    return $pdf->download('ingresos-egresos-semanales.pdf');
  }

  /**
   * Muestra el listado general de los Ingresos y Egresos Semanales con su Total
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosMensualesGeneral()
  {
    // Tomo los Registros (movimientos extra) diarios con su total
    $movExtras = MovExtras::select(DB::raw('fecha, sum(monto) as total, tipo'))
                            ->groupBy('fecha', 'tipo')
                            ->get();

    // Tomo las reservas de Inmuebles diarias con su total
    $reservasInmueble = ReservaInmueble::select(DB::raw("fechaSolicitud, sum(costoTotal) as total, 'Ingreso' as tipo"))
                             ->where('numRecibo', '<>', null)
                             ->groupBy('fechaSolicitud')
                             ->get();

    // Tomo las reservas de Muebles diarias con su total
    $reservasMueble = ReservaMueble::select(DB::raw("fechaSolicitud, sum(costoTotal) as total, 'Ingreso' as tipo"))
                             ->where('numRecibo', '<>', null)
                             ->groupBy('fechaSolicitud')
                             ->get();

    // Tomo las Cuotas pagadas diarias 
    $cuotasPagadas = ComprobanteCuota::all()
                                       ->where('fechaPago', '<>', null)
                                       ->where('inhabilitada', false);

    // Calculo el Monto Total de cada Cuota Pagada
    foreach ($cuotasPagadas as $cuotaPagada) {
      $cuotaPagada = $this->calculaMontoCuotaPagada($cuotaPagada);
    }

    // Acumulo los Montos de las Cuotas Pagadas en un array asociativo con KEY fecha
    $totales = array();

    // Inicializo los valores del array en 0 (cero)
    foreach ($movExtras as $movExtra) {
      $fecha = Carbon::parse($movExtra->fecha);
      $mes = $fecha->month;
      $anio = $fecha->year;

      $totales[$anio." - ".$mes] = array("total" => 0, "mes" => $mes, "anio" => $anio);
    }

    foreach ($reservasInmueble as $reservaInmueble) {
      $fecha = Carbon::parse($reservaInmueble->fechaSolicitud);
      $mes = $fecha->month;
      $anio = $fecha->year;

      $totales[$anio." - ".$mes] = array("total" => 0, "mes" => $mes, "anio" => $anio);
    }

    foreach ($reservasMueble as $reservaMueble) {
      $fecha = Carbon::parse($reservaMueble->fechaSolicitud);
      $mes = $fecha->month;
      $anio = $fecha->year;

      $totales[$anio." - ".$mes] = array("total" => 0, "mes" => $mes, "anio" => $anio);
    }

    foreach ($cuotasPagadas as $cuotaPagada) {
      $fecha = Carbon::parse($cuotaPagada->fechaPago);
      $mes = $fecha->month;
      $anio = $fecha->year;

      $totales[$anio." - ".$mes] = array("total" => 0, "mes" => $mes, "anio" => $anio);
    }

    // Acumulo los totales
    foreach ($movExtras as $movExtra) {
      $fecha = Carbon::parse($movExtra->fecha);
      $mes = $fecha->month;
      $anio = $fecha->year;

      if($movExtra->tipo == 1) {
        $totales[$anio." - ".$mes]["total"] += $movExtra->total;
      } elseif ($movExtra->tipo == 2) {
        $totales[$anio." - ".$mes]["total"] -= $movExtra->total;
      }
    }

    foreach ($reservasInmueble as $reservaInmueble) {
      $fecha = Carbon::parse($reservaInmueble->fechaSolicitud);
      $mes = $fecha->month;
      $anio = $fecha->year;

      $totales[$anio." - ".$mes]["total"] += $reservaInmueble->total;
    }

    foreach ($reservasMueble as $reservaMueble) {
      $fecha = Carbon::parse($reservaMueble->fechaSolicitud);
      $mes = $fecha->month;
      $anio = $fecha->year;

      $totales[$anio." - ".$mes]["total"] += $reservaMueble->total;
    }

    foreach ($cuotasPagadas as $cuotaPagada) {
      $fecha = Carbon::parse($cuotaPagada->fechaPago);
      $mes = $fecha->month;
      $anio = $fecha->year;

      $totales[$anio." - ".$mes]["total"] += $cuotaPagada->montoTotal;
    }

    return view('informe.ingresosEgresos.ingresosEgresosMensualesGenerales', compact('totales'));
  }

  /**
   * Shows a list of Ingresos/Egresos Mensuales
   * 
   * @param string $mes
   * @param string $anio
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosMensuales($mes, $anio)
  {
    $mes = intval($mes);
    $anio = intval($anio);

    //tomo los movimientos extra
    $movExtras = MovExtras::all();
    
    //filtro los movimientos extra que son mayores a 30 dias
    $movExtras = $movExtras->filter(function($movExtra) use ($mes, $anio) {
      //$now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($movExtra->fecha);
      $mesConsulta = $fechaFormateada->month;
      $anioConsulta = $fechaFormateada->year;

      return (($mesConsulta == $mes) && ($anioConsulta == $anio));
    });

    //tomo los alquileres de inmuebles pagados
    $alquileresInmueblePagos = ReservaInmueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de inmuebles que son mayores a 30 dias
    $alquileresInmueblePagos = $alquileresInmueblePagos->filter(function($alquilerInmueblePago) use ($mes, $anio) {
      //$now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($alquilerInmueblePago->fechaSolicitud);
      $mesConsulta = $fechaFormateada->month;
      $anioConsulta = $fechaFormateada->year;

      return (($mesConsulta == $mes) && ($anioConsulta == $anio));
    });

    //tomo los alquileres de muebles pagados
    $alquileresMueblePagos = ReservaMueble::all()->where('numRecibo', '<>', null);

    //filtro los alquileres de muebles que son mayores a 30 dias
    $alquileresMueblePagos = $alquileresMueblePagos->filter(function($alquilerMueblePago) use ($mes, $anio) {
      //$now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($alquilerMueblePago->fechaSolicitud);
      $mesConsulta = $fechaFormateada->month;
      $anioConsulta = $fechaFormateada->year;

      return (($mesConsulta == $mes) && ($anioConsulta == $anio));
    });

    //tomo los pagos de cuotas
    $cuotasPagadas = ComprobanteCuota::all()->where('fechaPago', '<>', null)->where('inhabilitada', false);
    
    //filtro los pagos de cuotas que son mayores a 30 dias
    $cuotasPagadas = $cuotasPagadas->filter(function($cuotaPagada) use ($mes, $anio) {
      //$now = $this->fechaHoy();
      $fechaFormateada = Carbon::parse($cuotaPagada->fechaPago);
      $mesConsulta = $fechaFormateada->month;
      $anioConsulta = $fechaFormateada->year;

      return (($mesConsulta == $mes) && ($anioConsulta == $anio));
    });

    //calculo el monto de las cuotas pagadas
    foreach($cuotasPagadas as $cuotaPagada) {
      $cuotaPagada = $this->calculaMontoCuotaPagada($cuotaPagada);
    }

    $mesAnio = $mes." - ".$anio;

    return view('informe.ingresosEgresos.ingresosEgresosMensuales', compact('movExtras', 
                                                                            'alquileresInmueblePagos',
                                                                            'alquileresMueblePagos',
                                                                            'cuotasPagadas',
                                                                            'mesAnio'));
  }

  /**
   * Generates a pdf of Ingresos y Egresos Mensuales.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfIngresosEgresosMensuales()
  {
    // Tomo los Registros (movimientos extra) diarios con su total
    $movExtras = MovExtras::select(DB::raw('fecha, sum(monto) as total, tipo'))
                            ->groupBy('fecha', 'tipo')
                            ->get();

    // Tomo las reservas de Inmuebles diarias con su total
    $reservasInmueble = ReservaInmueble::select(DB::raw("fechaSolicitud, sum(costoTotal) as total, 'Ingreso' as tipo"))
                             ->where('numRecibo', '<>', null)
                             ->groupBy('fechaSolicitud')
                             ->get();

    // Tomo las reservas de Muebles diarias con su total
    $reservasMueble = ReservaMueble::select(DB::raw("fechaSolicitud, sum(costoTotal) as total, 'Ingreso' as tipo"))
                             ->where('numRecibo', '<>', null)
                             ->groupBy('fechaSolicitud')
                             ->get();

    // Tomo las Cuotas pagadas diarias 
    $cuotasPagadas = ComprobanteCuota::all()
                                       ->where('fechaPago', '<>', null)
                                       ->where('inhabilitada', false);

    // Calculo el Monto Total de cada Cuota Pagada
    foreach ($cuotasPagadas as $cuotaPagada) {
      $cuotaPagada = $this->calculaMontoCuotaPagada($cuotaPagada);
    }

    // Acumulo los Montos de las Cuotas Pagadas en un array asociativo con KEY fecha
    $totales = array();

    // Inicializo los valores del array en 0 (cero)
    foreach ($movExtras as $movExtra) {
      $fecha = Carbon::parse($movExtra->fecha);
      $mes = $fecha->month;
      $anio = $fecha->year;

      $totales[$anio." - ".$mes] = array("total" => 0, "mes" => $mes, "anio" => $anio);
    }

    foreach ($reservasInmueble as $reservaInmueble) {
      $fecha = Carbon::parse($reservaInmueble->fechaSolicitud);
      $mes = $fecha->month;
      $anio = $fecha->year;

      $totales[$anio." - ".$mes] = array("total" => 0, "mes" => $mes, "anio" => $anio);
    }

    foreach ($reservasMueble as $reservaMueble) {
      $fecha = Carbon::parse($reservaMueble->fechaSolicitud);
      $mes = $fecha->month;
      $anio = $fecha->year;

      $totales[$anio." - ".$mes] = array("total" => 0, "mes" => $mes, "anio" => $anio);
    }

    foreach ($cuotasPagadas as $cuotaPagada) {
      $fecha = Carbon::parse($cuotaPagada->fechaPago);
      $mes = $fecha->month;
      $anio = $fecha->year;

      $totales[$anio." - ".$mes] = array("total" => 0, "mes" => $mes, "anio" => $anio);
    }

    // Acumulo los totales
    foreach ($movExtras as $movExtra) {
      $fecha = Carbon::parse($movExtra->fecha);
      $mes = $fecha->month;
      $anio = $fecha->year;

      if($movExtra->tipo == 1) {
        $totales[$anio." - ".$mes]["total"] += $movExtra->total;
      } elseif ($movExtra->tipo == 2) {
        $totales[$anio." - ".$mes]["total"] -= $movExtra->total;
      }
    }

    foreach ($reservasInmueble as $reservaInmueble) {
      $fecha = Carbon::parse($reservaInmueble->fechaSolicitud);
      $mes = $fecha->month;
      $anio = $fecha->year;

      $totales[$anio." - ".$mes]["total"] += $reservaInmueble->total;
    }

    foreach ($reservasMueble as $reservaMueble) {
      $fecha = Carbon::parse($reservaMueble->fechaSolicitud);
      $mes = $fecha->month;
      $anio = $fecha->year;

      $totales[$anio." - ".$mes]["total"] += $reservaMueble->total;
    }

    foreach ($cuotasPagadas as $cuotaPagada) {
      $fecha = Carbon::parse($cuotaPagada->fechaPago);
      $mes = $fecha->month;
      $anio = $fecha->year;

      $totales[$anio." - ".$mes]["total"] += $cuotaPagada->montoTotal;
    }

    // Ordeno por semana/anio descendiente
    krsort($totales);

    $pdf = PDF::loadView('pdf.ingresosEgresosMensuales', ['totales' => $totales]);

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
      $cuotaPagada = $this->calculaMontoCuotaPagada($cuotaPagada);
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
      $cuotaPagada = $this->calculaMontoCuotaPagada($cuotaPagada);
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
