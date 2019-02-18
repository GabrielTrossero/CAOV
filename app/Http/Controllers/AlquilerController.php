<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AlquilerController extends Controller
{
  /**
   * Display the list of Alquileres to choose which of them will be paid.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Display the form with options on Alquileres' payment.
   * @param int $id
   * @return \Illuminate\Http\Response
   */
    public function getPago($id)
    {
      //
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
