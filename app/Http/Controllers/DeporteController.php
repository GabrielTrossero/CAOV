<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Deporte;

class DeporteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('deporte.menu');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //redirijo a la vista para agregar un deporte
          return view('deporte.agregar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $deporte = new Deporte;

        //valido los datos ingresados
        $validacion = Validator::make($request->all(),[
          'nombre' => 'required|max:75|unique:deporte'
        ]);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails())
        {
          return redirect()->back()->withErrors($validacion->errors());
        }

        //almaceno el deporte en la BD
        $deporte->create($request->all());

        $deporteRetornado = Deporte::where('nombre', $request->nombre)->first();

        return redirect()->action('DeporteController@getShowId', $deporteRetornado->id);

    }

    /**
     * Display the resource list
     *
     * @return \Illuminate\Http\Response
     */
    public function getShow()
    {
        //recupero todos los deportes de la BD
        $deportes = Deporte::all();

        //redirijo a la vista para listar todas las personas pasando el array 'deportes'
        return view('deporte.listado', compact('deportes'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShowId($id)
    {
        //busco el deporte
        $deporte = Deporte::find($id);

        //redirijo a la vista individual con los datos del deporte
        return view('deporte.individual', ['deporte' => $deporte]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //busco el deporte a editar en la BD
        $deporte = Deporte::find($id);

        //redirijo al formulario de edición con los datos del deporte
        return view('deporte.editar', ['deporte' => $deporte]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //valido los datos enviados
        $validacion = Validator::make($request->all(), [
          'nombre' => 'required|max:75|unique:deporte'
        ]);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails())
        {
          return redirect()->back()->withErrors($validacion->errors());
        }

        //busco el registro
        $deporte = Deporte::find($request->id);

        //reemplazo los datos enviados por el registro encontrado
        $deporte->nombre = $request->nombre;

        //guardo el registro
        $deporte->save();

        //redirijo a la vista individual
        return redirect()->action('DeporteController@getShowId', $deporte->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $deporte = Deporte::destroy($request->id);

        return redirect()->action('DeporteController@getShow');
    }
}
