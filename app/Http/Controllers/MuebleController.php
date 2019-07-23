<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Mueble;

class MuebleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mueble.menu');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //redirijo a la vista para agregar un mueble
        return view('mueble.agregar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mueble = new Mueble;

        //mensajes de error que se mostraran por pantalla
        $messages = [
          'nombre.required' => 'Es necesario ingresar un Nombre.',
          'nombre.max' => 'Es necesario ingresar un Nombre válido.',
          'nombre.unique' => 'Ya existe un Mueble con dicho Nombre.',
          'cantidad.required' => 'Es necesario ingresar una Cantidad.'
        ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(), [
          'nombre' => 'required|max:75|unique:mueble',
          'cantidad' => 'required|min:1'
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withInput()->withErrors($validacion->errors());
        }

        //almaceno la persona
        $mueble->create($request->all());

        $muebleRetornado = Mueble::where('nombre', $request->nombre)->first();

        //redirijo para mostrar el mueble ingresado
        return redirect()->action('MuebleController@getShowId', $muebleRetornado->id);
    }

    /**
     * Display the resource list
     *
     * @return \Illuminate\Http\Response
     */
    public function getShow()
    {
      //busco todas los muebles
      $muebles = Mueble::all();

      //redirijo a la vista para listar todos los muebles pasando el array 'muebles'
      return view('mueble.listado' , compact('muebles'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShowId($id)
    {
      //busco el mueble
      $mueble = Mueble::find($id);

      //redirijo a la vista individual con los datos del mueble
      return view('mueble.individual' , ['mueble' => $mueble]);
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
      $mueble = Mueble::find($id);

      //redirijo al formulario de edicion con los datos del mueble
      return view('mueble.editar' , ['mueble' => $mueble]);
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
        'nombre.unique' => 'Ya existe un Mueble con dicho Nombre.',
        'accionCantidad.required' => 'Es necesario seleccionar una opción.',
        'accionCantidad.in' => 'Dicha opción no es válida.',
        'cantidadModificar.required_if' => 'Es necesario ingresar una Cantidad a Modificar.'
      ];

      //valido los datos ingresados
      $validacion = Validator::make($request->all(), [
        'nombre' => [
          'required',
          'max:75',
          Rule::unique('mueble')->ignore($request->id),
        ],
        'accionCantidad' => 'required|in:0,1,2',
        'cantidadModificar' => 'required_if:accionCantidad,1,2'
      ], $messages);

      //si la validacion falla vuelvo hacia atras con los errores
      if($validacion->fails()){
        return redirect()->back()->withInput()->withErrors($validacion->errors());
      }

      //busco el registro
      $mueble = Mueble::find($request->id);

      //verifico si hay que agregar, restar o no hacer nada
      if ($request->accionCantidad == 1) {
        $request->cantidadModificar = $mueble->cantidad - $request->cantidadModificar;
      }
      elseif ($request->accionCantidad == 2) {
        $request->cantidadModificar = $mueble->cantidad + $request->cantidadModificar;
      }
      else {
        $request->cantidadModificar = $mueble->cantidad;
      }

      //actualizo dicho registro
      Mueble::where('id', $request->id)
            ->update([
              'nombre' => $request->nombre,
              'cantidad' => $request->cantidadModificar
            ]);

      //retorno a la vista el socio actualizado
      return redirect()->action('MuebleController@getShowId', $request->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      //elimino el registro con tal id
      $mueble = Mueble::destroy($request->id);

      //redirijo al listado
      return redirect()->action('MuebleController@getShow');
    }
}
