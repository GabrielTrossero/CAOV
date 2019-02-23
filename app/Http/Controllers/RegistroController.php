<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegistroController extends Controller
{
  /**
   * Display the form to add a Registro.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('registro.agregar');
  }

  /**
   * Add the Registro.
   * @param Request $request
   *
   * @return \Illuminate\Http\Response
   */
  public function postRegistro(Request $request)
  {
    //
  }
}
