<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
  public function postSocioDeudor(Request $request)
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
    return view('informe.cantidadSocios');
  }

  /**
   * Generates a pdf of Cantidad de Socios.
   *
   * @return \Illuminate\Http\Response
   */
  public function postCantidadSocios()
  {
    //
  }

  /**
   * Show a list with Cantidad de Socios por Deporte.
   *
   * @return \Illuminate\Http\Response
   */
  public function getCantidadSociosDeporte()
  {
    return view('informe.cantidadSociosDeporte');
  }

  /**
   * Generates a pdf of Cantidad de Socios por Deporte.
   *
   * @return \Illuminate\Http\Response
   */
  public function postCantidadSociosDeporte()
  {
    //
  }

  /**
   * Show a list with Ingresos y Egresos.
   *
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresos()
  {
    return view('informe.ingresosEgresos');
  }

  /**
   * Generates a pdf of Ingresos y Egresos.
   *
   * @return \Illuminate\Http\Response
   */
  public function postIngresosEgresos()
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
  public function postPagos()
  {
    //
  }
}
