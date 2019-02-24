<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PagoAlquilerController extends Controller
{
    /**
    * Display the list of Alquileres to choose which of them will be paid.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
      return view('pagoAlquiler.menu');
    }

    /**
    * Display the resource list
    *
   * @return \Illuminate\Http\Response
    */
    public function getShowMueble()
    {
      return view('pagoAlquiler.listaMuebles');
    }

    /**
    * Display the resource list
    *
    * @return \Illuminate\Http\Response
    */
    public function getShowInmueble()
    {
      return view('pagoAlquiler.listaInmuebles');
    }

    /**
    * Show the options on the specified resource
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function getPagoMueble($id)
    {
      return view('pagoAlquiler.ingresarPagoMueble');
    }

    /**
     * Add the payment and generate a pdf or send it via email.
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postPagoMueble(Request $request)
    {
      //
    }

    /**
    * Show the options on the specified resource
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function getPagoInmueble($id)
    {
      return view('pagoalquiler.ingresarPagoInmueble');
    }

    /**
     * Add the payment and generate a pdf or send it via email.
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postPagoInmueble(Request $request)
    {
      //
    }
}
