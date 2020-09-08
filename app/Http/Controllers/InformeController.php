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
use \stdClass;

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
   * funcion que se utiliza en getDeudores() y pdfDeudores()
   * @param  null
   * @return App\Socio $socios
   */
  private function deudores()
  {
    //obtengo todos los socios y los ordeno por numSocio (para mostrarlos ordenados en el PDF)
    $socios = Socio::orderBy('numSocio', 'ASC')->get();

    //para llamar a las funciones que están en CuotaController
    $cuotaController = new CuotaController;

    //recorro socio y cada cuota
    foreach ($socios as $socio) {
      $cantCuotas = 0;
      $montoDeuda = 0;
      
      foreach ($socio->comprobantesDeCuotas as $cuota) {
        //cuento cuantas cuotas tiene sin pagar y sumo lo que debe hasta el mes actual
        if (($cuota->fechaPago == null)&&($cuota->inhabilitada == false)) {
          $cantCuotas++;

          $cuota->fechaPago = Carbon::Now(); //le seteo la fecha actual para calcular el interes de atraso
          $montoDeuda += $cuotaController->montoInteresAtraso($cuota);
          $montoDeuda += $cuotaController->montoInteresGrupoFamiliar($cuota);
          $montoDeuda += $cuota->montoCuota->montoMensual;
        }
      }
      $socio->cantCuotas = $cantCuotas;
      $socio->montoDeuda = $montoDeuda;
    }

    //funcion para filtrar y no eviarle los socios que no deben cuotas
    $socios = $socios->filter(function ($value, $key) {
      if ($value->cantCuotas != 0)
        return true;
      else false;
    });

    return $socios;
  }

  /**
   * retorna un objeto JSON de cantidad de socios por numero de cuotas que adeudan
   * 
   * @param Socio $socios
   */
  public function graficoTortaCantidadCuotasAdeudadasPorSocios($socios)
  {
    $tortaCantidadCuotasAdeudadas = $this->getObjetoParaGraficaDeTorta();
    $tortaCantidadCuotasAdeudadas->data->datasets[0]->label = "Cantidad de Socios por Cuotas adeudadas";

    foreach($socios as $socio){
      if(!in_array($socio->cantCuotas, $tortaCantidadCuotasAdeudadas->data->labels)) {
        $tortaCantidadCuotasAdeudadas->data->labels[] = $socio->cantCuotas." cuotas";
        $tortaCantidadCuotasAdeudadas->data->datasets[0]->data[$socio->cantCuotas] = 0;
      }

      $tortaCantidadCuotasAdeudadas->data->datasets[0]->data[$socio->cantCuotas] += 1;
    }

    sort($tortaCantidadCuotasAdeudadas->data->labels);
    ksort($tortaCantidadCuotasAdeudadas->data->datasets[0]->data);
    $tortaCantidadCuotasAdeudadas->data->datasets[0]->data = array_values($tortaCantidadCuotasAdeudadas->data->datasets[0]->data);

    return json_encode($tortaCantidadCuotasAdeudadas);
  }

  /**
   * retorna un objeto genérico para graficos de torta por categoria
   */
  public function getObjetoParaGraficaDeTortaPorCategoria($objetoGraficaTorta)
  {
    $objetoGraficaTorta->data->labels = ["activos", "cadetes", "grupos familiares"];
    $objetoGraficaTorta->data->datasets[0]->data["activos"] = 0;
    $objetoGraficaTorta->data->datasets[0]->data["cadetes"] = 0;
    $objetoGraficaTorta->data->datasets[0]->data["grupos familiares"] = 0;

    return $objetoGraficaTorta;
  }

  /**
   * retorna un objeto JSON de cantidad de cuotas que se adeudan por categoria
   *
   * @param Socio $socios
   */
  public function graficoTortaCantidadCuotasAdeudadasPorCategoria($socios)
  {
    $tortaCantidadCuotasAdeudadas = $this->getObjetoParaGraficaDeTorta();
    $tortaCantidadCuotasAdeudadas = $this->getObjetoParaGraficaDeTortaPorCategoria($tortaCantidadCuotasAdeudadas);

    $cuotaController = new CuotaController;

    foreach ($socios as $socio) {
      if (isset($socio->idGrupoFamiliar)) {
        $tortaCantidadCuotasAdeudadas->data->datasets[0]->data["grupos familiares"] += $socio->cantCuotas;
      } else if ($cuotaController->calculaEdad($socio) >= 18) {
        $tortaCantidadCuotasAdeudadas->data->datasets[0]->data["activos"] += $socio->cantCuotas;
      } else if ($cuotaController->calculaEdad($socio) < 18) {
        $tortaCantidadCuotasAdeudadas->data->datasets[0]->data["cadetes"] += $socio->cantCuotas;
      }
    }

    $tortaCantidadCuotasAdeudadas->data->datasets[0]->data = array_values($tortaCantidadCuotasAdeudadas->data->datasets[0]->data);

    return json_encode($tortaCantidadCuotasAdeudadas);
  }

  /**
   * retorna un objeto JSON del monto total adeudado por categoria
   *
   * @param Socio $socios
   */
  public function graficoTortaMontoTotalAdeudadoPorCategoria($socios)
  {
    $tortaTotalAdeudadoPorCategoria = $this->getObjetoParaGraficaDeTorta();
    $tortaTotalAdeudadoPorCategoria = $this->getObjetoParaGraficaDeTortaPorCategoria($tortaTotalAdeudadoPorCategoria);

    $cuotaController = new CuotaController;

    foreach ($socios as $socio) {
      if (isset($socio->idGrupoFamiliar)) {
        $tortaTotalAdeudadoPorCategoria->data->datasets[0]->data["grupos familiares"] += $socio->montoDeuda;
      } else if ($cuotaController->calculaEdad($socio) >= 18) {
        $tortaTotalAdeudadoPorCategoria->data->datasets[0]->data["activos"] += $socio->montoDeuda;
      } else if ($cuotaController->calculaEdad($socio) < 18) {
        $tortaTotalAdeudadoPorCategoria->data->datasets[0]->data["cadetes"] += $socio->montoDeuda;
      }
    }

    $tortaTotalAdeudadoPorCategoria->data->datasets[0]->data = array_values($tortaTotalAdeudadoPorCategoria->data->datasets[0]->data);

    return json_encode($tortaTotalAdeudadoPorCategoria);
  }

  /**
   * Show a list of Socios Deudores.
   *
   * @return \Illuminate\Http\Response
   */
  public function getDeudores()
  {
    $socios = $this->deudores();
    $tortaCantidadCuotasAdeudadas = $this->graficoTortaCantidadCuotasAdeudadasPorSocios($socios);
    $tortaMontoTotalAdeudadoPorCategoria = $this->graficoTortaMontoTotalAdeudadoPorCategoria($socios);
    
    return view('informe.sociosDeudores', compact('socios',
                                                  'tortaCantidadCuotasAdeudadas',
                                                  'tortaMontoTotalAdeudadoPorCategoria'));
  }

  /**
   * Generates a pdf of Socios Deudores.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfDeudores()
  {
    //llamo a la función deudores
    $socios = $this->deudores();
    $tortaCantidadCuotasAdeudadas = $this->graficoTortaCantidadCuotasAdeudadasPorSocios($socios);
    $tortaMontoTotalAdeudadoPorCategoria = $this->graficoTortaMontoTotalAdeudadoPorCategoria($socios);

    $pdf = PDF::loadView('pdf.deudores', ['socios' => $socios,
                                          'tortaCantidadCuotasAdeudadas' => $tortaCantidadCuotasAdeudadas,
                                          'tortaMontoTotalAdeudadoPorCategoria' => $tortaMontoTotalAdeudadoPorCategoria]);

    return $pdf->download('deudores.pdf');
  }

  /**
   * funcion que se utiliza en getSocioDeudor() y pdfSocioDeudor()
   * @param  int
   * @return App\Socio $socios
   */
  private function deudor($id)
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
    
    $montoTotal = 0;
    foreach ($cuotasNoPagadas as $cuota) {
      $cuota->fechaPago = Carbon::Now(); //le seteo la fecha actual para calcular el interes de atraso

      //sumo lo que debe hasta el mes actual
      $montoDeuda = 0;
      $montoDeuda += $cuotaController->montoInteresAtraso($cuota);
      $montoDeuda += $cuotaController->montoInteresGrupoFamiliar($cuota);
      $montoDeuda += $cuota->montoCuota->montoMensual;
      
      $cuota->fechaPago = null; //le vuelvo a poner en null la fecha de pago
      $cuota->montoDeuda = $montoDeuda; //le seteo el monto

      $montoTotal += $montoDeuda; //sumo lo que debe entre todas las cuotas
    }

    $socio->cuotasNoPagadas = $cuotasNoPagadas;
    $socio->montoTotal = $montoTotal;

    return $socio;
  }

  /**
   * Show detail of Socio Deudor.
   *
   * @return \Illuminate\Http\Response
   */
  public function getSocioDeudor($id)
  {
    //llamo a la función deudor
    $socio = $this->deudor($id);

    return view('informe.socioDeudor', compact('socio'));
  }

  /**
   * Generates a pdf of Socio Deudor.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfSocioDeudor(Request $request)
  {
    ///llamo a la función deudor
    $socio = $this->deudor($request->id);

    $pdf = PDF::loadView('pdf.socioDeudor', ['socio' => $socio]);

    return $pdf->download('socio-deudor.pdf');
  }

  /**
   * retorna un objeto JSON para grafica de linea de socios nuevos y dados de baja del corriente mes
   * 
   * @param Socio[] $socios
   * @return String
   */
  public function graficoLineaSociosNuevosYBajasMensual($socios)
  {
    $objetoGraficaLineaNuevosYBajas = $this->getObjetoParaGraficaDeLineaSociosNuevosYBajas();
    $cuotaController = new CuotaController;
    $fechaHoy = Carbon::now();

    foreach ($socios as $socio) {
      if (isset($socio->fechaBaja) && (Carbon::parse($socio->fechaBaja)->month == $fechaHoy->month)) {
        $fechaBaja = Carbon::parse($socio->fechaBaja)->format("d-m-Y");
        if (!in_array($fechaBaja, $objetoGraficaLineaNuevosYBajas->data->labels)) {
          $objetoGraficaLineaNuevosYBajas->data->labels[] = date("d-m-Y", strtotime($fechaBaja));
          $objetoGraficaLineaNuevosYBajas->data->datasets[0]->data[$fechaBaja] = 0;
          $objetoGraficaLineaNuevosYBajas->data->datasets[1]->data[$fechaBaja] = 0;
        }
        
        $objetoGraficaLineaNuevosYBajas->data->datasets[1]->data[$fechaBaja] += 1;
      } else if (Carbon::parse($socio->fechaCreacion)->month == $fechaHoy->month) {
        $fechaCreacion = Carbon::parse($socio->fechaCreacion)->format("d-m-Y");
        if (!in_array($fechaCreacion, $objetoGraficaLineaNuevosYBajas->data->labels)) {
          $objetoGraficaLineaNuevosYBajas->data->labels[] = date("d-m-Y", strtotime($fechaCreacion));
          $objetoGraficaLineaNuevosYBajas->data->datasets[0]->data[$fechaCreacion] = 0;
          $objetoGraficaLineaNuevosYBajas->data->datasets[1]->data[$fechaCreacion] = 0;
        }
        
        $objetoGraficaLineaNuevosYBajas->data->datasets[0]->data[$fechaCreacion] += 1;
      }
    }
    
    sort($objetoGraficaLineaNuevosYBajas->data->labels);
    ksort($objetoGraficaLineaNuevosYBajas->data->datasets[0]->data);
    ksort($objetoGraficaLineaNuevosYBajas->data->datasets[1]->data);
    $objetoGraficaLineaNuevosYBajas->data->datasets[0]->data = array_values($objetoGraficaLineaNuevosYBajas->data->datasets[0]->data);
    $objetoGraficaLineaNuevosYBajas->data->datasets[1]->data = array_values($objetoGraficaLineaNuevosYBajas->data->datasets[1]->data);

    return json_encode($objetoGraficaLineaNuevosYBajas);
  }

  /**
   * retorna un objeto JSON de la cantidad de cadetes que pasan a activos 
   *
   * @param Socio $socios
   * @return String
   */
  public function graficoBarraSociosCadetesPasanActivos($socios) 
  {
    $fechaHoy = Carbon::now();
    $fechaHoyMenosDosAnios = Carbon::now()->subYears(2);
    $fechaHoyMenosUnAnio = Carbon::now()->subYears(1);
    $fechaHoyMasDosAnios = Carbon::now()->addYears(2);
    $fechaHoyMasUnAnio = Carbon::now()->addYears(1);
    $objetoGraficaBarraCadetes = $this->getObjetoParaGraficaDeBarra();
    $objetoGraficaBarraCadetes->data->datasets[0]->label = "Cantidad de Cadetes";

    $cuotaController = new CuotaController;

    foreach ($socios as $socio) {
      $fechaNacimiento = Carbon::parse($socio->fechaNac);
      if ((($fechaHoyMenosDosAnios->year - $fechaNacimiento->year) == 18)
        || (($fechaHoyMenosUnAnio->year - $fechaNacimiento->year) == 18)
        || (($fechaHoy->year - $fechaNacimiento->year) == 18)
        || (($fechaHoyMasUnAnio->year - $fechaNacimiento->year) == 18)
        || (($fechaHoyMasDosAnios->year - $fechaNacimiento->year) == 18)) {
          if (!in_array($fechaNacimiento->year + 18, $objetoGraficaBarraCadetes->data->labels)) {
            $objetoGraficaBarraCadetes->data->labels[] = $fechaNacimiento->year + 18;
            $objetoGraficaBarraCadetes->data->datasets[0]->data[$fechaNacimiento->year + 18] = 0;
          }
          $objetoGraficaBarraCadetes->data->datasets[0]->data[$fechaNacimiento->year + 18] += 1;
      }
    }

    sort($objetoGraficaBarraCadetes->data->labels);
    ksort($objetoGraficaBarraCadetes->data->datasets[0]->data);
    $objetoGraficaBarraCadetes->data->datasets[0]->data = array_values($objetoGraficaBarraCadetes->data->datasets[0]->data);
    
    return json_encode($objetoGraficaBarraCadetes);
  }

  /**
   * retorna un objeto JSON de la cantidad de socios nuevos y dados de baja del ultimo semestre
   *
   * @param Socio[] $socios
   * @return String
   */
  public function graficoDonaSociosNuevosYBajasSemestral($socios)
  {
    $objetoGraficaDona = $this->getObjetoParaGraficaDeDona();
    $fechaHoy = Carbon::now();
    $fechaHoyMenosSeisMeses = Carbon::now()->subMonths(6);
    $totalMovimientoDeSociosSeisMeses = 0;

    foreach ($socios as $socio) {
      $fechaCreacion = Carbon::parse($socio->fechaCreacion);
      $fechaBaja = Carbon::parse($socio->fechaBaja);
      if (isset($socio->fechaBaja) && $fechaBaja->between($fechaHoyMenosSeisMeses, $fechaHoy)) {
        if (!in_array("Dados de Baja", $objetoGraficaDona->data->labels)) {
          $objetoGraficaDona->data->labels[] = "Dados de Baja";
          $objetoGraficaDona->data->datasets[0]->data["Dados de Baja"] = 0;
        }
        $objetoGraficaDona->data->datasets[0]->data["Dados de Baja"] += 1;
        $totalMovimientoDeSociosSeisMeses += 1;
      } else if ($fechaCreacion->between($fechaHoyMenosSeisMeses, $fechaHoy)){
        if (!in_array("Nuevos", $objetoGraficaDona->data->labels)) {
          $objetoGraficaDona->data->labels[] = "Nuevos";
          $objetoGraficaDona->data->datasets[0]->data["Nuevos"] = 0;
        }
        $objetoGraficaDona->data->datasets[0]->data["Nuevos"] += 1;
        $totalMovimientoDeSociosSeisMeses += 1;
      }
    }
    
    $objetoGraficaDona->options->plugins->doughnutlabel->labels[0]->text = "".$totalMovimientoDeSociosSeisMeses;
    $objetoGraficaDona->data->datasets[0]->data = array_values($objetoGraficaDona->data->datasets[0]->data);
    
    return json_encode($objetoGraficaDona);
  }

  /**
   * Show the Cantidad de Socios.
   *
   * @return \Illuminate\Http\Response
   */
  public function getCantidadSocios()
  {
    //tomo todos los socios
    $socios = Socio::with('deportes')->get();

    $lineaSociosNuevosYBajas = $this->graficoLineaSociosNuevosYBajasMensual($socios);
    $barraSociosCadetesPasanActivos = $this->graficoBarraSociosCadetesPasanActivos($socios);
    $donaSociosNuevosYBajasUltimosSeisMeses = $this->graficoDonaSociosNuevosYBajasSemestral($socios);
    
    //retorno la vista con la cantidad de socios
    return view('informe.cantidadSocios', compact('lineaSociosNuevosYBajas',
                                                  'barraSociosCadetesPasanActivos',
                                                  'donaSociosNuevosYBajasUltimosSeisMeses'));
  }

  /**
   * Generates a pdf of Cantidad de Socios.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfCantidadSocios()
  {
    //tomo todos los socios
    $socios = Socio::with('deportes')->get();

    $lineaSociosNuevosYBajas = $this->graficoLineaSociosNuevosYBajasMensual($socios);
    $barraSociosCadetesPasanActivos = $this->graficoBarraSociosCadetesPasanActivos($socios);
    $donaSociosNuevosYBajasUltimosSeisMeses = $this->graficoDonaSociosNuevosYBajasSemestral($socios);

    $pdf = PDF::loadView('pdf.cantidadSocios', ['lineaSociosNuevosYBajas' => $lineaSociosNuevosYBajas,
                                                'barraSociosCadetesPasanActivos' => $barraSociosCadetesPasanActivos,
                                                'donaSociosNuevosYBajasUltimosSeisMeses' => $donaSociosNuevosYBajasUltimosSeisMeses]);

    return $pdf->download('cantidad-socios.pdf');
  }

  /**
   * retorna graficas de linea de socios nuevos y dados de baja, de dona de nuevos/bajas de los 
   * ultimos seis meses, y egresos/egresos mensuales
   * 
   * @return String
   */
  public function graficasParaHome()
  {
    $socios = Socio::with('deportes')->get();
    $montos = $this->ingresosEgresosMensuales();

    $lineaSociosNuevosYBajas = $this->graficoLineaSociosNuevosYBajasMensual($socios);
    $donaSociosNuevosYBajasUltimosSeisMeses = $this->graficoDonaSociosNuevosYBajasSemestral($socios);
    $lineaBalanceIngresosEgresosMensual = $this->graficoLineaBalanceIngresosEgresosMensuales($montos);

    return view('menu.home', compact('lineaSociosNuevosYBajas',
                                     'donaSociosNuevosYBajasUltimosSeisMeses',
                                     'lineaBalanceIngresosEgresosMensual'));
  }

  /**
   * retorna un objeto genérico para graficos de linea
   */
  public function getObjetoParaGraficaDeLinea()
  {
    $objetoGraficaLinea = new stdClass;
    $objetoGraficaLinea->type = "line";
    $objetoGraficaLinea->data = new stdClass;
    $objetoGraficaLinea->data->labels = array();
    $objetoGraficaLinea->data->datasets = array();
    $objetoGraficaLinea->data->datasets[0] = new stdClass;
    $objetoGraficaLinea->data->datasets[0]->data = array();
    $objetoGraficaLinea->data->datasets[0]->fill = false;
    $objetoGraficaLinea->data->datasets[0]->borderColor = 'blue';
    $objetoGraficaLinea->data->datasets[1] = new stdClass;
    $objetoGraficaLinea->data->datasets[1]->data = array();
    $objetoGraficaLinea->data->datasets[1]->fill = false;
    $objetoGraficaLinea->data->datasets[1]->borderColor = 'green';

    return $objetoGraficaLinea;
  }

  /**
   * genera un objeto genérico para grafico de linea de Ingresos y Egresos
   */
  public function getObjetoParaGraficaDeLineaIngresosEgresos()
  {
    $objetoGraficaLinea = $this->getObjetoParaGraficaDeLinea();
    $objetoGraficaLinea->data->datasets[0]->label = "Ingresos";
    $objetoGraficaLinea->data->datasets[1]->label = "Egresos";

    return $objetoGraficaLinea;
  }

  /**
   * genera un objeto genérico para grafico de linea de Ingresos y Egresos
   */
  public function getObjetoParaGraficaDeLineaSociosNuevosYBajas()
  {
    $objetoGraficaLinea = $this->getObjetoParaGraficaDeLinea();
    $objetoGraficaLinea->data->datasets[0]->label = "Nuevos";
    $objetoGraficaLinea->data->datasets[1]->label = "Dados de Baja";

    return $objetoGraficaLinea;
  }

  /**
   * genera un objeto genérico para grafico de dona
   */
  public function getObjetoParaGraficaDeDona()
  {
    $objetoGraficaDona = new stdClass;
    $objetoGraficaDona->type = "doughnut";
    $objetoGraficaDona->data = new stdClass;
    $objetoGraficaDona->data->labels = array();
    $objetoGraficaDona->data->datasets = array();
    $objetoGraficaDona->data->datasets[0] = new stdClass;
    $objetoGraficaDona->data->datasets[0]->data = array();
    $objetoGraficaDona->options = new stdClass;
    $objetoGraficaDona->options->plugins = new stdClass;
    $objetoGraficaDona->options->plugins->doughnutlabel = new stdClass;
    $objetoGraficaDona->options->plugins->doughnutlabel->labels = array();
    $objetoGraficaDona->options->plugins->doughnutlabel->labels[0] = new stdClass;
    $objetoGraficaDona->options->plugins->doughnutlabel->labels[0]->text = '0';
    $objetoGraficaDona->options->plugins->doughnutlabel->labels[0]->font = new stdClass;
    $objetoGraficaDona->options->plugins->doughnutlabel->labels[0]->font->size = 20;
    $objetoGraficaDona->options->plugins->doughnutlabel->labels[1] = new stdClass;
    $objetoGraficaDona->options->plugins->doughnutlabel->labels[1]->text = 'total';
    $objetoGraficaDona->options->plugins->doughnutlabel->labels[1]->font = new stdClass;
    $objetoGraficaDona->options->plugins->doughnutlabel->labels[1]->font->size = 15;

    return $objetoGraficaDona;
  }

  /**
   * retorna un objeto genérico para graficos de torta
   */
  public function getObjetoParaGraficaDeTorta()
  {
    $objetoGraficaTorta = new stdClass;
    $objetoGraficaTorta->type = "pie";
    $objetoGraficaTorta->data = new stdClass;
    $objetoGraficaTorta->data->labels = array();
    $objetoGraficaTorta->data->datasets = array();
    $objetoGraficaTorta->data->datasets[0] = new stdClass;
    $objetoGraficaTorta->data->datasets[0]->data = array();

    return $objetoGraficaTorta;
  }

  /**
   * retorna un objeto genérico para graficos de barra
   */
  public function getObjetoParaGraficaDeBarra()
  {
    $objetoGraficaBarra = new stdClass;
    $objetoGraficaBarra->type = "bar";
    $objetoGraficaBarra->data = new stdClass;
    $objetoGraficaBarra->data->labels = array();
    $objetoGraficaBarra->data->datasets = array();
    $objetoGraficaBarra->data->datasets[0] = new stdClass;
    $objetoGraficaBarra->data->datasets[0]->data = array();

    return $objetoGraficaBarra;
  }

  /**
   * retorna un objeto JSON de cantidad de socios por deporte
   * 
   * @param Deporte $deportes
   * @return String
   */
  public function graficoBarraSociosPorDeporte($deportes)
  {
    $barraSociosPorDeporte = $this->getObjetoParaGraficaDeBarra();
    $barraSociosPorDeporte->data->datasets[0]->label = "Socios por Deporte";

    foreach ($deportes as $deporte) {
        $barraSociosPorDeporte->data->labels[] = $deporte->nombre;
        $barraSociosPorDeporte->data->datasets[0]->data[] = $deporte->cantidadSocios;
    }

    return $barraSociosPorDeporte = json_encode($barraSociosPorDeporte);
  }

  /**
   * retorna un objeto JSON de cantidad de socios activos por deporte
   *
   * @return void
   */
  public function graficoBarraActivosPorDeporte($deportes)
  {
    $barraActivosPorDeporte = $this->getObjetoParaGraficaDeBarra();
    $barraActivosPorDeporte->data->datasets[0]->label = "Socios Activos por Deporte";

    $indexDeporte = 0;
    $cuotaController = new CuotaController;

    foreach ($deportes as $deporte) {
      $barraActivosPorDeporte->data->labels[] = $deporte->nombre;
      $barraActivosPorDeporte->data->datasets[0]->data[$indexDeporte] = 0;
      foreach ($deporte->socios as $socio) {
        if (($cuotaController->calculaEdad($socio) >= 18) && !isset($socio->idGrupoFamiliar)) {
          $barraActivosPorDeporte->data->datasets[0]->data[$indexDeporte] += 1;
        }
      }
      $indexDeporte += 1;
    }

    return json_encode($barraActivosPorDeporte);
  }

  /**
   * retorna un objeto JSON de cantidad de socios cadetes por deporte
   *
   * @return void
   */
  public function graficoBarraCadetesPorDeporte($deportes)
  {
    $barraCadetesPorDeporte = $this->getObjetoParaGraficaDeBarra();
    $barraCadetesPorDeporte->data->datasets[0]->label = "Socios Cadetes por Deporte";

    $indexDeporte = 0;
    $cuotaController = new CuotaController;

    foreach ($deportes as $deporte) {
      $barraCadetesPorDeporte->data->labels[] = $deporte->nombre;
      $barraCadetesPorDeporte->data->datasets[0]->data[$indexDeporte] = 0;
      foreach ($deporte->socios as $socio) {
        if (($cuotaController->calculaEdad($socio) < 18) && !isset($socio->idGrupoFamiliar)) {
          $barraCadetesPorDeporte->data->datasets[0]->data[$indexDeporte] += 1;
        }
      }
      $indexDeporte += 1;
    }

    return json_encode($barraCadetesPorDeporte);
  }

  /**
   * retorna un objeto JSON de cantidad de socios con grupo familiar por deporte
   *
   * @return void
   */
  public function graficoBarraSociosConGrupoPorDeporte($deportes)
  {
    $barraSociosConGrupoPorDeporte = $this->getObjetoParaGraficaDeBarra();
    $barraSociosConGrupoPorDeporte->data->datasets[0]->label = "Socios Con Grupo Familiar por Deporte";

    $indexDeporte = 0;

    foreach ($deportes as $deporte) {
      $barraSociosConGrupoPorDeporte->data->labels[] = $deporte->nombre;
      $barraSociosConGrupoPorDeporte->data->datasets[0]->data[$indexDeporte] = 0;
      foreach ($deporte->socios as $socio) {
        if (isset($socio->idGrupoFamiliar)) {
         $barraSociosConGrupoPorDeporte->data->datasets[0]->data[$indexDeporte] += 1;
        }
      }
      
      $indexDeporte += 1;
    }

    return json_encode($barraSociosConGrupoPorDeporte);
  }

  /**
   * retorna un objeto JSON de personas que practican X deportes
   *
   * @return void
   */
  public function graficoBarraCantidadDeportesPracticadosPorSocios($socios)
  {
    $barraCantidadDeportesPracticados = $this->getObjetoParaGraficaDeBarra();

    $barraCantidadDeportesPracticados->data->datasets[0]->label = "Cantidad de Socios por Deportes Practicados";

    foreach ($socios as $socio) {
      $cantidadDeportes = sizeof($socio->deportes);
      if(!in_array($cantidadDeportes, $barraCantidadDeportesPracticados->data->labels)) {
        $barraCantidadDeportesPracticados->data->labels[] = $cantidadDeportes." deportes";
        $barraCantidadDeportesPracticados->data->datasets[0]->data[$cantidadDeportes] = 0;
      }

      $barraCantidadDeportesPracticados->data->datasets[0]->data[$cantidadDeportes] += 1;
    }

    sort($barraCantidadDeportesPracticados->data->labels);
    ksort($barraCantidadDeportesPracticados->data->datasets[0]->data);
    $barraCantidadDeportesPracticados->data->datasets[0]->data = array_values($barraCantidadDeportesPracticados->data->datasets[0]->data);

    return json_encode($barraCantidadDeportesPracticados);
  }

  /**
   * Show a list with Cantidad de Socios por Deporte.
   *
   * @return \Illuminate\Http\Response
   */
  public function getCantidadSociosDeporte()
  {
    //tomo todos los deportes
    $deportes = Deporte::with('socios')->get();
    $socios = Socio::where('activo','<>',0)->with('deportes')->get();

    //calculco la cantidad de socios de cada deporte
    foreach ($deportes as $deporte) {
      $deporte->cantidadSocios = sizeof($deporte->socios);
    }

    $barraSociosPorDeporte = $this->graficoBarraSociosPorDeporte($deportes);
    $barraActivosPorDeporte = $this->graficoBarraActivosPorDeporte($deportes);
    $barraCadetesPorDeporte = $this->graficoBarraCadetesPorDeporte($deportes);
    $barraSociosConGrupoPorDeporte = $this->graficoBarraSociosConGrupoPorDeporte($deportes);
    $barraCantidadDeportesPracticados = $this->graficoBarraCantidadDeportesPracticadosPorSocios($socios); 

    //retorno la vista con la cantidad de socios por deporte
    return view('informe.cantidadSociosDeporte', compact(['barraSociosPorDeporte',
                                                          'barraActivosPorDeporte',
                                                          'barraCadetesPorDeporte',
                                                          'barraSociosConGrupoPorDeporte',
                                                          'barraCantidadDeportesPracticados']));
  }

  /**
   * Generates a pdf of Cantidad de Socios por Deporte.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfCantidadSociosDeporte()
  {
    //tomo todos los deportes
    $deportes = Deporte::with('socios')->get();
    $socios = Socio::where('activo','<>',0)->with('deportes')->get();

    //calculco la cantidad de socios de cada deporte
    foreach ($deportes as $deporte) {
      $deporte->cantidadSocios = sizeof($deporte->socios);
    }

    $barraSociosPorDeporte = $this->graficoBarraSociosPorDeporte($deportes);
    $barraActivosPorDeporte = $this->graficoBarraActivosPorDeporte($deportes);
    $barraCadetesPorDeporte = $this->graficoBarraCadetesPorDeporte($deportes);
    $barraSociosConGrupoPorDeporte = $this->graficoBarraSociosConGrupoPorDeporte($deportes);
    $barraCantidadDeportesPracticados = $this->graficoBarraCantidadDeportesPracticadosPorSocios($socios);

    $pdf = PDF::loadView('pdf.cantidadSociosDeporte', ['barraSociosPorDeporte' => $barraSociosPorDeporte,
                                                       'barraActivosPorDeporte' => $barraActivosPorDeporte,
                                                       'barraCadetesPorDeporte' => $barraCadetesPorDeporte,
                                                       'barraSociosConGrupoPorDeporte' => $barraSociosConGrupoPorDeporte,
                                                       'barraCantidadDeportesPracticados' => $barraCantidadDeportesPracticados]);

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
   * funcion que se utiliza en los métodos de ingresos/egresos diarios, semanales y mensuales
   * @param  null
   * @return $variable
   */
  private function variablesIngresosEgresos()
  {
    // Tomo los Registros (movimientos extra) con su total
    $movExtras = MovExtras::select(DB::raw('fecha, sum(monto) as montoTotal, tipo'))
                            ->groupBy('fecha', 'tipo')
                            ->get();

    // Tomo las reservas de Inmuebles con su total
    $reservasInmueble = ReservaInmueble::select(DB::raw("fechaSolicitud, sum(costoTotal) as montoTotal, 'Ingreso' as tipo"))
                             ->where('numRecibo', '<>', null)
                             ->groupBy('fechaSolicitud')
                             ->get();

    // Tomo las reservas de Muebles con su total
    $reservasMueble = ReservaMueble::select(DB::raw("fechaSolicitud, sum(costoTotal) as montoTotal, 'Ingreso' as tipo"))
                             ->where('numRecibo', '<>', null)
                             ->groupBy('fechaSolicitud')
                             ->get();

    // Tomo las Cuotas pagadas 
    $cuotasPagadas = ComprobanteCuota::all()
                                       ->where('fechaPago', '<>', null)
                                       ->where('inhabilitada', false);

    // Calculo el Monto Total de cada Cuota Pagada
    foreach ($cuotasPagadas as $cuotaPagada) {
      $cuotaPagada = $this->calculaMontoCuotaPagada($cuotaPagada);
    }

    //variable en la que voy a retornar todo
    $variable = new \Illuminate\Database\Eloquent\Collection;
    $variable->movExtras = $movExtras;
    $variable->reservasInmueble = $reservasInmueble;
    $variable->reservasMueble = $reservasMueble;
    $variable->cuotasPagadas = $cuotasPagadas;

    return $variable;
  }

  /**
   * funcion que se utiliza en getIngresosEgresosDiariosGeneral() y pdfIngresosEgresosDiarios()
   * @param  null
   * @return $totales
   */
  private function ingresosEgresosDiarios()
  {
    //llamo a la función para obtener las variables a utilizar
    $variable = $this->variablesIngresosEgresos();

    // Acumulo los montos de Ingresos y Egresos en arrays asociativos con KEY fecha
    $ingresos = array();
    $egresos = array();

    // Inicializo los valores de los arrays en 0 (cero)
    foreach ($variable->movExtras as $movExtra) {
      $ingresos[$movExtra->fecha] = 0;
      $egresos[$movExtra->fecha] = 0;
    }

    foreach ($variable->reservasInmueble as $reservaInmueble) {
      $ingresos[$reservaInmueble->fechaSolicitud] = 0;
      $egresos[$reservaInmueble->fechaSolicitud] = 0;
    }

    foreach ($variable->reservasMueble as $reservaMueble) {
      $ingresos[$reservaMueble->fechaSolicitud] = 0;
      $egresos[$reservaMueble->fechaSolicitud] = 0;
    }

    foreach ($variable->cuotasPagadas as $cuotaPagada) {
      $ingresos[$cuotaPagada->fechaPago] = 0;
      $egresos[$cuotaPagada->fechaPago] = 0;
    }

    // Acumulo los montos
    foreach ($variable->movExtras as $movExtra) {
      if($movExtra->tipo == 1) {
        $ingresos[$movExtra->fecha] += $movExtra->montoTotal;
      } elseif ($movExtra->tipo == 2) {
        $egresos[$movExtra->fecha] += $movExtra->montoTotal;
      }
    }

    foreach ($variable->reservasInmueble as $reservaInmueble) {
      $ingresos[$reservaInmueble->fechaSolicitud] += $reservaInmueble->montoTotal;
    }

    foreach ($variable->reservasMueble as $reservaMueble) {
      $ingresos[$reservaMueble->fechaSolicitud] += $reservaMueble->montoTotal;
    }

    foreach ($variable->cuotasPagadas as $cuotaPagada) {
      $ingresos[$cuotaPagada->fechaPago] += $cuotaPagada->montoTotal;
    }

    //variable en la que voy a retornar todo
    $montos = new \Illuminate\Database\Eloquent\Collection;
    $montos->ingresos = $ingresos;
    $montos->egresos = $egresos;

    return $montos;
  }

  /**
   * retorna un objeto JSON para grafica de linea del balance diario (14 dias) de ingresos y egresos
   *
   * @param Collection $montos
   * @return void
   */
  public function graficoLineaBalanceIngresosEgresosDiarios($montos)
  {
    $lineaBalanceDiario = $this->getObjetoParaGraficaDeLineaIngresosEgresos();
    $fechaHoy = Carbon::now();
    $fechaHoyMenosCatorceDias = Carbon::now()->subDays(14);

    foreach($montos->ingresos as $fecha => $monto) {
      if(Carbon::parse($fecha)->between($fechaHoyMenosCatorceDias, $fechaHoy)) {
        if(!in_array($fecha, $lineaBalanceDiario->data->labels)) {
          $lineaBalanceDiario->data->labels[] = date("d-m-Y", strtotime($fecha));
          $lineaBalanceDiario->data->datasets[0]->data[$fecha] = 0;
          $lineaBalanceDiario->data->datasets[1]->data[$fecha] = 0;
        }

        $lineaBalanceDiario->data->datasets[0]->data[$fecha] += $monto;
      }
    }

    foreach($montos->egresos as $fecha => $monto) {
      if(Carbon::parse($fecha)->between($fechaHoyMenosCatorceDias, $fechaHoy)) {
        $lineaBalanceDiario->data->datasets[1]->data[$fecha] += $monto;
      }
    }

    $lineaBalanceDiario->data->datasets[0]->data = array_values($lineaBalanceDiario->data->datasets[0]->data);
    $lineaBalanceDiario->data->datasets[1]->data = array_values($lineaBalanceDiario->data->datasets[1]->data);

    return json_encode($lineaBalanceDiario);
  }

  /**
   * Muestra el listado general de los Ingresos y Egresos Diarios con su Total
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosDiariosGeneral() 
  {
    //llamo a la función ingresosEgresosDiarios
    $montos = $this->ingresosEgresosDiarios();
    $lineaBalanceIngresosEgresosDiarios = $this->graficoLineaBalanceIngresosEgresosDiarios($montos);

    return view('informe.ingresosEgresos.ingresosEgresosDiariosGenerales', compact('montos',
                                                                                   'lineaBalanceIngresosEgresosDiarios'));
  }

  /**
   * Generates a pdf of Ingresos y Egresos Diarios.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfIngresosEgresosDiarios()
  {
    //llamo a la función ingresosEgresosDiarios
    $montos = $this->ingresosEgresosDiarios();

    // Ordeno por fecha acendente
    ksort($montos->ingresos);

    $lineaBalanceIngresosEgresosDiarios =  $this->graficoLineaBalanceIngresosEgresosDiarios($montos);

    $pdf = PDF::loadView('pdf.ingresosEgresosDiarios', ['montos' => $montos,
                                                        'lineaBalanceIngresosEgresosDiarios' => $lineaBalanceIngresosEgresosDiarios]);

    return $pdf->download('ingresos-egresos-diarios.pdf');
  }

  /**
   * Shows a list of Ingresos/Egresos diarios
   * 
   * @param string $fecha
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosDiarios($fecha, $balance)
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
                                                                          'fecha',
                                                                          'balance'));
  }

  /**
   * funcion que se utiliza en getIngresosEgresosSemanalesGeneral() y pdfIngresosEgresosSemanales()
   * @param  null
   * @return $totales
   */
  private function ingresosEgresosSemanales()
  {
    //llamo a la función para obtener las variables a utilizar
    $variable = $this->variablesIngresosEgresos();

    // Acumulo los montos de Ingresos y Egresos en arrays asociativos con KEY fecha
    $ingresos = array();
    $egresos = array();

    // Inicializo los valores de los arrays en 0 (cero)
    foreach ($variable->movExtras as $movExtra) {
      $fecha = Carbon::parse($movExtra->fecha);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      if ($semana < 10) {
        $semana = "0".$semana;
      }
      
      $ingresos[$anio." - ".$semana] = 0;
      $egresos[$anio." - ".$semana] = 0;
    }

    foreach ($variable->reservasInmueble as $reservaInmueble) {
      $fecha = Carbon::parse($reservaInmueble->fechaSolicitud);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;

      if ($semana < 10) {
        $semana = "0".$semana;
      }

      $ingresos[$anio." - ".$semana] = 0;
      $egresos[$anio." - ".$semana] = 0;
    }

    foreach ($variable->reservasMueble as $reservaMueble) {
      $fecha = Carbon::parse($reservaMueble->fechaSolicitud);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;
      
      if ($semana < 10) {
        $semana = "0".$semana;
      }

      $ingresos[$anio." - ".$semana] = 0;
      $egresos[$anio." - ".$semana] = 0;
    }

    foreach ($variable->cuotasPagadas as $cuotaPagada) {
      $fecha = Carbon::parse($cuotaPagada->fechaPago);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;
      
      if ($semana < 10) {
        $semana = "0".$semana;
      }

      $ingresos[$anio." - ".$semana] = 0;
      $egresos[$anio." - ".$semana] = 0;
    }

    // Acumulo los montos
    foreach ($variable->movExtras as $movExtra) {
      $fecha = Carbon::parse($movExtra->fecha);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;
      
      if ($semana < 10) {
        $semana = "0".$semana;
      }

      if($movExtra->tipo == 1) {
        $ingresos[$anio." - ".$semana] += $movExtra->montoTotal;
      } elseif ($movExtra->tipo == 2) {
        $egresos[$anio." - ".$semana] += $movExtra->montoTotal;
      }
    }

    foreach ($variable->reservasInmueble as $reservaInmueble) {
      $fecha = Carbon::parse($reservaInmueble->fechaSolicitud);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;
      
      if ($semana < 10) {
        $semana = "0".$semana;
      }

      $ingresos[$anio." - ".$semana] += $reservaInmueble->montoTotal;
    }

    foreach ($variable->reservasMueble as $reservaMueble) {
      $fecha = Carbon::parse($reservaMueble->fechaSolicitud);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;
      
      if ($semana < 10) {
        $semana = "0".$semana;
      }

      $ingresos[$anio." - ".$semana] += $reservaMueble->montoTotal;
    }

    foreach ($variable->cuotasPagadas as $cuotaPagada) {
      $fecha = Carbon::parse($cuotaPagada->fechaPago);
      $semana = $fecha->weekOfYear;
      $anio = $fecha->year;
      
      if ($semana < 10) {
        $semana = "0".$semana;
      }

      $ingresos[$anio." - ".$semana] += $cuotaPagada->montoTotal;
    }

    //variable en la que voy a retornar todo
    $montos = new \Illuminate\Database\Eloquent\Collection;
    $montos->ingresos = $ingresos;
    $montos->egresos = $egresos;

    return $montos;
  }

  /**
   * retorna un objeto JSON con el balance de las ultimas 8 semanas de ingresos y egresos
   *
   * @param Collection $montos
   * @return string
   */
  public function graficoLineaBalanceIngresosEgresosSemanales($montos)
  {
    ksort($montos->ingresos);
    ksort($montos->egresos);
    $lineaBalanceSemanal = $this->getObjetoParaGraficaDeLineaIngresosEgresos();
    $fechaHoy = Carbon::now();
    $fechaHoyMenosOchoSemanas = Carbon::now()->subWeeks(8);

    foreach($montos->ingresos as $fecha => $monto) {
      $anio = substr($fecha, 0, 4);
      $semana = substr($fecha, 7, 2);
      $fechaSemana = Carbon::parse($anio)->setISODate($anio, $semana);

      if($fechaSemana->between($fechaHoyMenosOchoSemanas, $fechaHoy)) {
        if(!in_array($fecha, $lineaBalanceSemanal->data->labels)) {
          $lineaBalanceSemanal->data->labels[] = $fecha;
          $lineaBalanceSemanal->data->datasets[0]->data[$fecha] = 0;
          $lineaBalanceSemanal->data->datasets[1]->data[$fecha] = 0;
        }

        $lineaBalanceSemanal->data->datasets[0]->data[$fecha] += $monto;
      }
    }

    foreach($montos->egresos as $fecha => $monto) {
      $anio = substr($fecha, 0, 4);
      $semana = substr($fecha, 7, 2);
      $fechaSemana = Carbon::parse($anio)->setISODate($anio, $semana);

      if($fechaSemana->between($fechaHoyMenosOchoSemanas, $fechaHoy)) {
        $lineaBalanceSemanal->data->datasets[1]->data[$fecha] += $monto;
      }
    }

    $lineaBalanceSemanal->data->datasets[0]->data = array_values($lineaBalanceSemanal->data->datasets[0]->data);
    $lineaBalanceSemanal->data->datasets[1]->data = array_values($lineaBalanceSemanal->data->datasets[1]->data);
    
    return json_encode($lineaBalanceSemanal);
  }

  /**
   * Muestra el listado general de los Ingresos y Egresos Semanales con su Total
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosSemanalesGeneral() 
  {
    //llamo a la función ingresosEgresosSemanales
    $montos = $this->ingresosEgresosSemanales();
    $lineaBalanceIngresosEgresosSemanales = $this->graficoLineaBalanceIngresosEgresosSemanales($montos);
    
    return view('informe.ingresosEgresos.ingresosEgresosSemanalesGenerales', compact('montos',
                                                                                     'lineaBalanceIngresosEgresosSemanales'));
  }

  /**
   * Generates a pdf of Ingresos y Egresos Semanales.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfIngresosEgresosSemanales()
  {
    //llamo a la función ingresosEgresosSemanales
    $montos = $this->ingresosEgresosSemanales();
    $lineaBalanceIngresosEgresosSemanales = $this->graficoLineaBalanceIngresosEgresosSemanales($montos);

    // Ordeno por semana/anio acendente
    ksort($montos->ingresos);

    $pdf = PDF::loadView('pdf.ingresosEgresosSemanales', ['montos' => $montos,
                                                          'lineaBalanceIngresosEgresosSemanales' => $lineaBalanceIngresosEgresosSemanales]);

    return $pdf->download('ingresos-egresos-semanales.pdf');
  }

  /**
   * Shows a list of Ingresos/Egresos Semanales
   * 
   * @param string $semana
   * @param string $anio
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosSemanales($semana, $balance)
  {
    //recibo la semana en formato "año - semana". Ej: (2020 - 02)
    //entonces lo corto de la cadena y lo transformo a int
    $anio = intval(substr($semana,0,4));
    $semana = intval(substr($semana,7,9));

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
                                                                            'semanaAnio',
                                                                            'balance'));
  }

  /**
   * funcion que se utiliza en getIngresosEgresosMensualesGeneral() y pdfIngresosEgresosMensuales()
   * @param  null
   * @return $totales
   */
  private function ingresosEgresosMensuales()
  {
    //llamo a la función para obtener las variables a utilizar
    $variable = $this->variablesIngresosEgresos();

    // Acumulo los montos de Ingresos y Egresos en arrays asociativos con KEY fecha
    $ingresos = array();
    $egresos = array();

    // Inicializo los valores de los arrays en 0 (cero)
    foreach ($variable->movExtras as $movExtra) {
      $fecha = Carbon::parse($movExtra->fecha);
      $mes = $fecha->month;
      $anio = $fecha->year;

      if ($mes < 10) {
        $mes = "0".$mes;
      }

      $ingresos[$anio." - ".$mes] = 0;
      $egresos[$anio." - ".$mes] = 0;
    }

    foreach ($variable->reservasInmueble as $reservaInmueble) {
      $fecha = Carbon::parse($reservaInmueble->fechaSolicitud);
      $mes = $fecha->month;
      $anio = $fecha->year;
      
      if ($mes < 10) {
        $mes = "0".$mes;
      }

      $ingresos[$anio." - ".$mes] = 0;
      $egresos[$anio." - ".$mes] = 0;
    }

    foreach ($variable->reservasMueble as $reservaMueble) {
      $fecha = Carbon::parse($reservaMueble->fechaSolicitud);
      $mes = $fecha->month;
      $anio = $fecha->year;
      
      if ($mes < 10) {
        $mes = "0".$mes;
      }

      $ingresos[$anio." - ".$mes] = 0;
      $egresos[$anio." - ".$mes] = 0;
    }

    foreach ($variable->cuotasPagadas as $cuotaPagada) {
      $fecha = Carbon::parse($cuotaPagada->fechaPago);
      $mes = $fecha->month;
      $anio = $fecha->year;
      
      if ($mes < 10) {
        $mes = "0".$mes;
      }

      $ingresos[$anio." - ".$mes] = 0;
      $egresos[$anio." - ".$mes] = 0;
    }

    // Acumulo los montos
    foreach ($variable->movExtras as $movExtra) {
      $fecha = Carbon::parse($movExtra->fecha);
      $mes = $fecha->month;
      $anio = $fecha->year;
      
      if ($mes < 10) {
        $mes = "0".$mes;
      }

      if($movExtra->tipo == 1) {
        $ingresos[$anio." - ".$mes] += $movExtra->montoTotal;
      } elseif ($movExtra->tipo == 2) {
        $egresos[$anio." - ".$mes] += $movExtra->montoTotal;
      }
    }

    foreach ($variable->reservasInmueble as $reservaInmueble) {
      $fecha = Carbon::parse($reservaInmueble->fechaSolicitud);
      $mes = $fecha->month;
      $anio = $fecha->year;
      
      if ($mes < 10) {
        $mes = "0".$mes;
      }

      $ingresos[$anio." - ".$mes] += $reservaInmueble->montoTotal;
    }

    foreach ($variable->reservasMueble as $reservaMueble) {
      $fecha = Carbon::parse($reservaMueble->fechaSolicitud);
      $mes = $fecha->month;
      $anio = $fecha->year;
      
      if ($mes < 10) {
        $mes = "0".$mes;
      }

      $ingresos[$anio." - ".$mes] += $reservaMueble->montoTotal;
    }

    foreach ($variable->cuotasPagadas as $cuotaPagada) {
      $fecha = Carbon::parse($cuotaPagada->fechaPago);
      $mes = $fecha->month;
      $anio = $fecha->year;
      
      if ($mes < 10) {
        $mes = "0".$mes;
      }

      $ingresos[$anio." - ".$mes] += $cuotaPagada->montoTotal;
    }

    //variable en la que voy a retornar todo
    $montos = new \Illuminate\Database\Eloquent\Collection;
    $montos->ingresos = $ingresos;
    $montos->egresos = $egresos;

    return $montos;
  }

  /**
   * retorna un objeto JSON con el balance de los ultimos 12 meses de ingresos y egresos
   *
   * @param Collection $montos
   * @return string
   */
  public function graficoLineaBalanceIngresosEgresosMensuales($montos)
  {
    ksort($montos->ingresos);
    ksort($montos->egresos);
    $lineaBalanceMensual = $this->getObjetoParaGraficaDeLineaIngresosEgresos();
    $fechaHoy = Carbon::now();
    $fechaHoyMenosDoceMeses = Carbon::now()->subMonths(12);

    foreach($montos->ingresos as $fecha => $monto) {
      $anio = substr($fecha, 0, 4);
      $mes = substr($fecha, 7, 2);
      $fechaMes = Carbon::parse($anio."-".$mes);

      if($fechaMes->between($fechaHoyMenosDoceMeses, $fechaHoy)) {
        if(!in_array($fecha, $lineaBalanceMensual->data->labels)) {
          $lineaBalanceMensual->data->labels[] = $fecha;
          $lineaBalanceMensual->data->datasets[0]->data[$fecha] = 0;
          $lineaBalanceMensual->data->datasets[1]->data[$fecha] = 0;
        }

        $lineaBalanceMensual->data->datasets[0]->data[$fecha] += $monto;
      }
    }

    foreach($montos->egresos as $fecha => $monto) {
      $anio = substr($fecha, 0, 4);
      $mes = substr($fecha, 7, 2);
      $fechaMes = Carbon::parse($anio."-".$mes);

      if($fechaMes->between($fechaHoyMenosDoceMeses, $fechaHoy)) {
        $lineaBalanceMensual->data->datasets[1]->data[$fecha] += $monto;
      }
    }
    
    $lineaBalanceMensual->data->datasets[0]->data = array_values($lineaBalanceMensual->data->datasets[0]->data);
    $lineaBalanceMensual->data->datasets[1]->data = array_values($lineaBalanceMensual->data->datasets[1]->data);
    
    return json_encode($lineaBalanceMensual);
  }


  /**
   * Muestra el listado general de los Ingresos y Egresos Semanales con su Total
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosMensualesGeneral()
  {
    //llamo a la función ingresosEgresosMensuales
    $montos = $this->ingresosEgresosMensuales();
    $lineaBalanceIngresosEgresosMensual = $this->graficoLineaBalanceIngresosEgresosMensuales($montos);
    
    return view('informe.ingresosEgresos.ingresosEgresosMensualesGenerales', compact('montos',
                                                                                     'lineaBalanceIngresosEgresosMensual'));
  }

  /**
   * Generates a pdf of Ingresos y Egresos Mensuales.
   *
   * @return \Illuminate\Http\Response
   */
  public function pdfIngresosEgresosMensuales()
  {
    //llamo a la función ingresosEgresosMensuales
    $montos = $this->ingresosEgresosMensuales();
    $lineaBalanceIngresosEgresosMensual = $this->graficoLineaBalanceIngresosEgresosMensuales($montos);

    // Ordeno por mes/anio acendente
    ksort($montos->ingresos);

    $pdf = PDF::loadView('pdf.ingresosEgresosMensuales', ['montos' => $montos,
                                                          'lineaBalanceIngresosEgresosMensual' => $lineaBalanceIngresosEgresosMensual]);

    return $pdf->download('ingresos-egresos-mensuales.pdf');
  }

  /**
   * Shows a list of Ingresos/Egresos Mensuales
   * 
   * @param string $mes
   * @param string $anio
   * 
   * @return \Illuminate\Http\Response
   */
  public function getIngresosEgresosMensuales($mes, $balance)
  {
    //recibo el mes en formato "año - mes". Ej: (2020 - 02)
    //entonces lo corto de la cadena y lo transformo a int
    $anio = intval(substr($mes,0,4));
    $mes = intval(substr($mes,7,9));

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

    if ($mes < 10) {
      $mes = "0".$mes;
    }

    $mesAnio = $mes." - ".$anio;

    return view('informe.ingresosEgresos.ingresosEgresosMensuales', compact('movExtras', 
                                                                            'alquileresInmueblePagos',
                                                                            'alquileresMueblePagos',
                                                                            'cuotasPagadas',
                                                                            'mesAnio',
                                                                            'balance'));
  }

  /**
   * retorna un objeto para grafica de torta por tipos de ingresos en pagos
   * 
   * @return stdClass
   */
  public function getObjetoParaGraficaDeTortaPorTipoIngreso($objetoGraficaTorta)
  {
    $objetoGraficaTorta->data->labels = ["cuotas", "alquiler inmuebles", "alquiler muebles"];
    $objetoGraficaTorta->data->datasets[0]->data["cuotas"] = 0;
    $objetoGraficaTorta->data->datasets[0]->data["inmuebles"] = 0;
    $objetoGraficaTorta->data->datasets[0]->data["muebles"] = 0;

    return $objetoGraficaTorta;
  }

  /**
   * retorna un objeto JSON del monto total pagado por tipo de ingreso
   *
   * @param ComprobanteCuota $cuotasPagadas
   * @param ReservaInmueble $reservasInmueble
   * @param ReservaMueble $reservasMueble
   * @param Integer $filtro [0: todas las fechas, 1: hoy, 2: esta semana, 3: este mes]
   * @return String
   */
  public function graficoTortaMontoTotalPorTipoDeIngreso($cuotasPagadas, $reservasInmueble, $reservasMueble, $filtro = 0)
  {
    $tortaMontoPagado = $this->getObjetoParaGraficaDeTorta();
    $tortaMontoPagado = $this->getObjetoParaGraficaDeTortaPorTipoIngreso($tortaMontoPagado);

    if ($filtro == 1) {
      $cuotasPagadas = $cuotasPagadas->filter(function ($cuota){
        return $this->fechaHoy()->format("Y-m-d") == $cuota->fechaPago;
      });
      $reservasInmueble = $reservasInmueble->filter(function ($reserva){
        return $this->fechaHoy()->format("Y-m-d") == $reserva->fechaSolicitud;
      });
      $reservasMueble = $reservasMueble->filter(function ($reserva){
        return $this->fechaHoy()->format("Y-m-d") == $reserva->fechaSolicitud;
      });
    } else if ($filtro == 2) {
      $cuotasPagadas = $cuotasPagadas->filter(function ($cuota){
        $fechaCuota = Carbon::parse($cuota->fechaPago);
        return (($this->fechaHoy()->year == $fechaCuota->year) 
                && ($this->fechaHoy()->weekOfYear == $fechaCuota->weekOfYear));
      });
      $reservasInmueble = $reservasInmueble->filter(function ($reserva){
        $fechaReserva = Carbon::parse($reserva->fechaSolicitud);
        return (($this->fechaHoy()->year == $fechaReserva->year) 
                && ($this->fechaHoy()->weekOfYear == $fechaReserva->weekOfYear));
      });
      $reservasMueble = $reservasMueble->filter(function ($reserva){
        $fechaReserva = Carbon::parse($reserva->fechaSolicitud);
        return (($this->fechaHoy()->year == $fechaReserva->year) 
                && ($this->fechaHoy()->weekOfYear == $fechaReserva->weekOfYear));
      });
    } else if ($filtro == 3) {
      $cuotasPagadas = $cuotasPagadas->filter(function ($cuota){
        $fechaCuota = Carbon::parse($cuota->fechaPago);
        return (($this->fechaHoy()->year == $fechaCuota->year) 
                && ($this->fechaHoy()->month == $fechaCuota->month));
      });
      $reservasInmueble = $reservasInmueble->filter(function ($reserva){
        $fechaReserva = Carbon::parse($reserva->fechaSolicitud);
        return (($this->fechaHoy()->year == $fechaReserva->year) 
                && ($this->fechaHoy()->month == $fechaReserva->month));
      });
      $reservasMueble = $reservasMueble->filter(function ($reserva){
        $fechaReserva = Carbon::parse($reserva->fechaSolicitud);
        return (($this->fechaHoy()->year == $fechaReserva->year) 
                && ($this->fechaHoy()->month == $fechaReserva->month));
      });
    }

    foreach ($cuotasPagadas as $cuota) {
      $tortaMontoPagado->data->datasets[0]->data["cuotas"] += $cuota->montoTotal;
    }
    foreach ($reservasInmueble as $reservaInmueble) {
      $tortaMontoPagado->data->datasets[0]->data["inmuebles"] += $reservaInmueble->costoTotal;
    }
    foreach ($reservasMueble as $reservaMueble) {
      $tortaMontoPagado->data->datasets[0]->data["muebles"] += $reservaMueble->costoTotal;
    }

    $tortaMontoPagado->data->datasets[0]->data = array_values($tortaMontoPagado->data->datasets[0]->data);

    return json_encode($tortaMontoPagado);
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
    $reservasInmueble = ReservaInmueble::with("inmueble")->where('numRecibo', '<>', null)->get();

    //tomo los alquileres de muebles
    $reservasMueble = ReservaMueble::with("mueble")->where('numRecibo', '<>', null)->get();

    $tortaMontoPorTipoDeIngreso = $this->graficoTortaMontoTotalPorTipoDeIngreso($cuotasPagadas, $reservasInmueble, $reservasMueble);
    $tortaMontoPorTipoDeIngresoHoy = $this->graficoTortaMontoTotalPorTipoDeIngreso($cuotasPagadas, $reservasInmueble, $reservasMueble, 1);
    $tortaMontoPorTipoDeIngresoSemana = $this->graficoTortaMontoTotalPorTipoDeIngreso($cuotasPagadas, $reservasInmueble, $reservasMueble, 2);
    $tortaMontoPorTipoDeIngresoMes = $this->graficoTortaMontoTotalPorTipoDeIngreso($cuotasPagadas, $reservasInmueble, $reservasMueble, 3);

    return view('informe.pagos', compact(['cuotasPagadas',
                                          'reservasInmueble',
                                          'reservasMueble',
                                          'tortaMontoPorTipoDeIngreso',
                                          'tortaMontoPorTipoDeIngresoHoy',
                                          'tortaMontoPorTipoDeIngresoSemana',
                                          'tortaMontoPorTipoDeIngresoMes']));
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

    $tortaMontoPorTipoDeIngreso = $this->graficoTortaMontoTotalPorTipoDeIngreso($cuotasPagadas, $reservasInmueble, $reservasMueble);
    $tortaMontoPorTipoDeIngresoHoy = $this->graficoTortaMontoTotalPorTipoDeIngreso($cuotasPagadas, $reservasInmueble, $reservasMueble, 1);
    $tortaMontoPorTipoDeIngresoSemana = $this->graficoTortaMontoTotalPorTipoDeIngreso($cuotasPagadas, $reservasInmueble, $reservasMueble, 2);
    $tortaMontoPorTipoDeIngresoMes = $this->graficoTortaMontoTotalPorTipoDeIngreso($cuotasPagadas, $reservasInmueble, $reservasMueble, 3);

    $pdf = PDF::loadView('pdf.pagos', ['cuotasPagadas' => $cuotasPagadas,
                                       'reservasInmueble' => $reservasInmueble,
                                       'reservasMueble' => $reservasMueble,
                                       'tortaMontoPorTipoDeIngreso' => $tortaMontoPorTipoDeIngreso,
                                       'tortaMontoPorTipoDeIngresoHoy' => $tortaMontoPorTipoDeIngresoHoy,
                                       'tortaMontoPorTipoDeIngresoSemana' => $tortaMontoPorTipoDeIngresoSemana,
                                       'tortaMontoPorTipoDeIngresoMes' => $tortaMontoPorTipoDeIngresoMes]);

    return $pdf->download('pagos.pdf');
  }
}
