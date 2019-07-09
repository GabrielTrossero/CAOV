<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PagoCuotaController extends Controller
{
  /**
   * Display the list of Socios to choose who paids the Cuota.
   *
   * @return \Illuminate\Http\Response
   */
  public function getShow()
  {
    return view('pagoCuota.listarSocios');
  }

/**
   * Display the the form to add the Socios's payment.
   * @param int $id
   * @return \Illuminate\Http\Response
   */
    public function getPago($id)
    {
      return view('pagoCuota.ingresarPago');
    }

    /**
     * Add the payment and generate a pdf or send it via email.
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
      public function postPago(Request $request)
      {
        //
      }
}
