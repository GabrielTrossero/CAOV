<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
    $success = false;
    /*if(strpos($respuesta, $comparador)){
      return redirect()->back()->with('backupExitoso', 'El Backup se ha llevado a cabo con éxito!');
    }
    else{
      return redirect()->back()->with('backupErroneo', 'El Backup NO se ha podido realizar con éxito.');
    }*/
    $success = strpos($respuesta, $comparador) > 0;

    return response()->json(['success' => $success]);
  }
}
