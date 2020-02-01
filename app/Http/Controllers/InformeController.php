<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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
    return view('informe.sociosDeudores');
  }

  /**
   * Generates a pdf of Socios Deudores.
   *
   * @return \Illuminate\Http\Response
   */
  public function postDeudores()
  {
    //
  }

  /**
   * Show detail of Socio Deudor.
   *
   * @return \Illuminate\Http\Response
   */
  public function getSocioDeudor($id)
  {
    return view('informe.socioDeudor');
  }

  /**
   * Generates a pdf of Socio Deudor.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfSocioDeudor(Request $request)
  {
    //
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
   * Show a list with Ingresos y Egresos.
   *
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresos()
  {
      //tomo todos los movimientos extras
      $movExtras = MovExtras::all();

      //tomo los alquileres de inmueble pagados
      $alquileresInmueblePagos = ReservaInmueble::selectRaw('MONTH(fechaHoraInicio) as mes, YEAR(fechaHoraInicio) as anio, SUM(costoTotal) as total')            ->where('numRecibo','<>',null)
                                                  ->groupBy(DB::raw('mes, anio'))->get();

      //tomo los alquileres de mueble pagados
      $alquileresMueblePagos = ReservaMueble::selectRaw('MONTH(fechaHoraInicio) as mes, YEAR(fechaHoraInicio) as anio, SUM(costoTotal) as total')              ->where('numRecibo','<>',null)
                                              ->groupBy(DB::raw('mes, anio'))->get();

      //tomo los pagos de cuotas
      $cuotasPagadas = ComprobanteCuota::selectRaw("MONTH(comprobantecuota.fechaPago) as mes, YEAR(comprobantecuota.fechaPago) as anio, SUM(CASE WHEN comprobantecuota.tipo = 'a' THEN montocuota.monto - (montocuota.monto * (montocuota.dtoAnio / 100)) WHEN comprobantecuota.tipo = 's' THEN montocuota.monto -(montocuota.monto * (montocuota.dtoSemestre / 100)) WHEN comprobantecuota.tipo = 'm' THEN montocuota.monto END) as total")
                                              ->join('montocuota','montocuota.id','=','comprobantecuota.idMontoCuota')
                                              ->groupBy(DB::raw('mes, anio'))->get();

      //redirijo a la vistas con los datos de ingresos/egresos
      return view('informe.ingresosEgresos', compact('movExtras', 'alquileresInmueblePagos', 'alquileresMueblePagos', 'cuotasPagadas'));

  }

  /**
   * Generates a pdf of Ingresos y Egresos.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfIngresosEgresos()
  {
    //
  }

  /**
   * Show a list with Pagos.
   *
   * @return \Illuminate\Http\Response
   */
  public function getPagos()
  {
    return view('informe.pagos');
  }

  /**
   * Generates a pdf of Pagos.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfPagos()
  {
    //
  }
}
