<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\CuotaController;

use App\MovExtras;
use App\ComprobanteCuota;
use App\ReservaInmueble;
use App\ReservaMueble;

class AdministradorController extends Controller
{
  /**
   * Show options on Administrador.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      return view('administrador.menu');
  }

  /**
   * Generates the DataBase Back Up.
   *
   * @return \Illuminate\Http\Response
   */
  public function postBackup()
  {
    //ejecuto el script en shell para el backup de la BD solamente
    $respuesta = shell_exec("cd .. & php artisan backup:run --only-db");
    $comparador = "Backup completed!";

    if(strpos($respuesta, $comparador)){
      return redirect()->back()->with('backupExitoso', 'El BackUp se ha llevado a cabo con éxito!');
    }
    else{
      return redirect()->back()->with('backupErroneo', 'El BackUp NO se ha podido realizar con éxito.');
    }
  }

  /**
   * Show a list of Ingresos.
   *
   * @return \Illuminate\Http\Response
   */
  public function getIngresos()
  {
    //tomo los ingresos de movimientos extra
    $movimientos = MovExtras::all()->where('tipo', '1');

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

    return view('administrador.ingresos', compact(['movimientos',
                                                   'cuotasPagadas',
                                                   'reservasInmueble',
                                                   'reservasMueble'
                                                   ])
                                                  );
  }
}
