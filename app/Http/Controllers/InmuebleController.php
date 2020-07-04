<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Inmueble;

class InmuebleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inmueble.menu');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //redirijo a la vista para agregar un inmueble
        return view('inmueble.agregar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $inmueble = new Inmueble;

      //mensajes de error que se mostraran por pantalla
      $messages = [
        'nombre.required' => 'Es necesario ingresar un Nombre.',
        'nombre.max' => 'Es necesario ingresar un Nombre válido.',
        'nombre.unique' => 'Ya existe un Inmueble con dicho Nombre.',
        'descripcion.max' => 'La Descripción no puede ser tan extensa.'
      ];

      //valido los datos ingresados
      $validacion = Validator::make($request->all(), [
        'nombre' => 'required|max:75|unique:inmueble',
        'descripcion' => 'max:75'
      ], $messages);

      //si la validacion falla vuelvo hacia atras con los errores
      if($validacion->fails()){
        return redirect()->back()->withInput()->withErrors($validacion->errors());
      }

      //almaceno la persona
      $inmueble->create($request->all());

      $inmuebleRetornado = Inmueble::where('nombre', $request->nombre)->first();

      //redirijo para mostrar el mueble ingresado
      return redirect()->action('InmuebleController@getShow');
    }

    /**
     * Display the resource list
     *
     * @return \Illuminate\Http\Response
     */
    public function getShow()
    {
        //busco todas los inmuebles
        $inmuebles = Inmueble::all();

        //redirijo a la vista para listar todos los inmuebles pasando el array 'inmuebles'
        return view('inmueble.listado' , compact('inmuebles'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShowId($id)
    {
        //busco el inmueble
        $inmueble = Inmueble::find($id);

        //redirijo a la vista individual con los datos del inmueble
        return view('inmueble.individual' , ['inmueble' => $inmueble]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //busco el registro
        $inmueble = Inmueble::find($id);

        //redirijo al formulario de edicion con los datos del inmueble
        return view('inmueble.editar' , ['inmueble' => $inmueble]);
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
        //mensajes de error que se mostraran por pantalla
        $messages = [
          'nombre.required' => 'Es necesario ingresar un Nombre.',
          'nombre.max' => 'Es necesario ingresar un Nombre válido.',
          'nombre.unique' => 'Ya existe un Inmueble con dicho Nombre.',
          'descripcion.max' => 'La Descripción no puede ser tan extensa.'
        ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(), [
          'nombre' => [
            'required',
            'max:75',
            Rule::unique('inmueble')->ignore($request->id)
          ],
          'descripcion' => 'max:75'
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withInput()->withErrors($validacion->errors());
        }

        //busco el registro
        $inmueble = Inmueble::find($request->id);

        //actualizo dicho registro
        Inmueble::where('id', $request->id)
              ->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion
              ]);

        //retorno a la vista el socio actualizado
        return redirect()->action('InmuebleController@getShow');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $inmueble = Inmueble::find($request->id);
        $cantidadAlquileresInmueble = sizeof($inmueble->reservasDeInmueble);

        if($cantidadAlquileresInmueble > 0)
        {
          return redirect()->back()->with('inmuebleTieneAlquileres', 'El Inmueble que se quiere eliminar tiene Alquileres asociados.');
        }
        
        //elimino el registro con tal id
        $inmueble = Inmueble::destroy($request->id);

        //redirijo al listado
        return redirect()->action('InmuebleController@getShow');
    }
}
