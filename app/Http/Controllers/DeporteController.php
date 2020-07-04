<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Deporte;
use Illuminate\Validation\Rule;

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

        //mensaje de error que se mostrará por pantalla
        $messages = [
          'nombre.required' => 'Es necesario ingresar un Nombre.',
          'nombre.max' => 'El Nombre no puede tener más de 75 caracteres.' ,
          'nombre.unique' => 'Ya existe dicho Deporte.'
        ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(),[
          'nombre' => 'required|max:75|unique:deporte'
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails())
        {
          return redirect()->back()->withErrors($validacion->errors());
        }

        //almaceno el deporte en la BD
        $deporte->create($request->all());

        $deporteRetornado = Deporte::where('nombre', $request->nombre)->first();

        return redirect()->action('DeporteController@getShow');
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
        //mensaje de error que se mostrará por pantalla
        $messages = [
          'nombre.required' => 'Es necesario ingresar un Nombre.',
          'nombre.max' => 'El Nombre no puede tener más de 75 caracteres.' ,
          'nombre.unique' => 'Ya existe dicho Deporte.'
        ];

        //valido los datos enviados
        $validacion = Validator::make($request->all(), [
          'nombre' => [
            'required',
            'max:75',
            Rule::unique('deporte')->ignore($request->id)
          ]
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails())
        {
          return redirect()->back()->withErrors($validacion->errors());
        }

        Deporte::where('id', $request->id)
              ->update([
                'nombre' => $request->nombre
              ]);

        //redirijo a la vista individual
        return redirect()->action('DeporteController@getShow');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $deporte = Deporte::find($request->id);
      $cantidadSociosDeporte = sizeof($deporte->socios);

      if($cantidadSociosDeporte > 0)
      {
        return redirect()->back()->with('deporteTieneSocios', 'El Deporte que se quiere eliminar tiene Socios anotados.');
      }

      $deporte = Deporte::destroy($request->id);

      return redirect()->action('DeporteController@getShow');
    }
}
