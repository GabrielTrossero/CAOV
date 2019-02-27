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
    //
  }

  /**
   * Show a list of Ingresos.
   *
   * @return \Illuminate\Http\Response
   */
  public function getIngresos()
  {
    return view('administrador.ingresos');
  }
}
