<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\GrupoFamiliar;
use App\Deporte;
use App\Socio;
use App\SocioDeporte;
use App\Persona;

class SocioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('socio.menu');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //almaceno todos los grupos familiares
        $grupos = GrupoFamiliar::all();

        //almaceno todos deportes
        $deportes = Deporte::all();

        //los envio a la vista del formulario
        return view('socio.agregar', compact(['grupos','deportes']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $socio = new Socio;
        $persona = new Persona;
        $socioRetornado = new Socio;

        //mensajes de error que se mostraran por pantalla
        $messages = [
          'numSocio.required' => 'Es necesario ingresar un Número de Socio.',
          'numSocio.unique' => 'Ya existe un Socio con dicho número.',
          'oficio.max' => 'La cantidad máxima de caracteres es 75.',
          'vitalicio.required' => 'Ingrese una opción válida.',
          'vitalicio.min' => 'Ingrese una opción válida.',
          'vitalicio.max' => 'Ingrese una opción válida.',
          'vitalicio.in' => 'Ingrese una opción válida.',
          'DNIPersona.required' => 'Es necesario ingresar un DNI válido.',
          'DNIPersona.min' => 'Es necesario ingresar un DNI válido.',
          'DNIPersona.max' => 'Es necesario ingresar un DNI válido.',
          'idGrupoFamiliar.required' => 'Es necesario ingresar una opción.',
        ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(),[
        'numSocio' => 'required|unique:socio',
        'oficio' => 'max:75',
        'vitalicio' => 'required|min:1|max:1|in:s,n',
        'DNIPersona' => 'required|min:8|max:8',
        'idGrupoFamiliar' => 'required'
        ], $messages);

        //para que le asigne null si es que no pertenece a ningun grupo familiar
        if($request->idGrupoFamiliar == 0)
          $request->idGrupoFamiliar = null;

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withInput()->withErrors($validacion->errors());
        }

        //almaceno al socio y el deporte que realiza
        $socio->numSocio = $request->numSocio;
        $socio->fechaNac = $request->fechaNac;
        $socio->oficio = $request->oficio;
        $socio->vitalicio = $request->vitalicio;
        $persona = Persona::where('DNI', $request->DNIPersona)->first();
        $socio->idPersona = $persona->id;
        $socio->idGrupoFamiliar = $request->idGrupoFamiliar;

        $socio->save();

        //cargo los deportes que realiza, si es que tiene
        if(isset($request->idDeporte)){
          $socio = Socio::where('numSocio', $request->numSocio)->first();

          foreach ($request->idDeporte as $value) {
            $socioDeporte = new SocioDeporte;
            $socioDeporte->idSocio = $socio->id;

            $socioDeporte->idDeporte = $value;
            $socioDeporte->save();
          }
        }

        $socioRetornado = Socio::where('numSocio', $request->numSocio)->first();

        //redirijo para mostrar el usuario ingresado
        return redirect()->action('SocioController@getShowId', $socioRetornado->id);
    }

    /**
     * Display the resource list
     *
     * @return \Illuminate\Http\Response
     */
    public function getShow()
    {
        //tomo todos los socios
        $socios = Socio::all();

        //los envio a la vista
        return view('socio.listado', compact('socios'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShowId($id)
    {
        //busco el socio
        $socio = Socio::find($id);

        //se lo envío a la vista
        return view('socio.individual', ['socio' => $socio]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //busco el socio
        $socio = Socio::find($id);

        //almaceno todos los grupos familiares
        $grupos = GrupoFamiliar::all();

        //almaceno todos deportes
        $deportes = Deporte::all();

        //almaceno SocioDeporte
        $socioDeporte = SocioDeporte::all();

        //se lo envío a la vista
        return view('socio.editar', compact(['socio','grupos','deportes','socioDeporte']));
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
        'numSocio.required' => 'Es necesario ingresar un Número de Socio.',
        'numSocio.unique' => 'Ya existe un Socio con dicho número.',
        'oficio.max' => 'La cantidad máxima de caracteres es 75.',
        'vitalicio.required' => 'Ingrese una opción válida.',
        'vitalicio.min' => 'Ingrese una opción válida.',
        'vitalicio.max' => 'Ingrese una opción válida.',
        'vitalicio.in' => 'Ingrese una opción válida.',
        'DNIPersona.required' => 'Es necesario ingresar un DNI válido.',
        'DNIPersona.min' => 'Es necesario ingresar un DNI válido.',
        'DNIPersona.max' => 'Es necesario ingresar un DNI válido.',
        'idGrupoFamiliar.required' => 'Es necesario ingresar una opción.',
      ];

      //valido los datos ingresados
      $validacion = Validator::make($request->all(),[
      'numSocio' => [
        'required',
        Rule::unique('socio')->ignore($request->id)
      ],
      'oficio' => 'max:75',
      'vitalicio' => 'required|min:1|max:1|in:s,n',
      'DNIPersona' => 'required|min:8|max:8',
      'idGrupoFamiliar' => 'required'
      ], $messages);

      //para que le asigne null si es que no pertenece a ningun grupo familiar
      if($request->idGrupoFamiliar == 0)
        $request->idGrupoFamiliar = null;

      //si la validacion falla vuelvo hacia atras con los errores
      if($validacion->fails()){
        return redirect()->back()->withInput()->withErrors($validacion->errors());
      }

      //para cargar el id de la persona a traves del DNI ingresado
      $persona = new Persona;
      $persona = Persona::where('DNI', $request->DNIPersona)->first();

      Socio::where('id', $request->id)
            ->update([
              'fechaNac' => $request->fechaNac,
              'idGrupoFamiliar' => $request->idGrupoFamiliar,
              'idPersona' => $persona->id,
              'numSocio' => $request->numSocio,
              'oficio' => $request->oficio,
              'vitalicio' => $request->vitalicio
            ]);

      //proceso para AGREGAR un nuevo deporte a dicho socio
      //si está vacía la variable que contiene los deportes que ahora realiza, que no entre
      if(isset($request->idDeporte)){
        foreach ($request->idDeporte as $deporteQueTiene) {
          $deporteQueTenia = new SocioDeporte;
          //busco si el socio realiza tal deporte (que ahora si realiza)
          $deporteQueTenia = SocioDeporte::where('idSocio', $request->id)
                              ->where('idDeporte', $deporteQueTiene)->first();
          //si el socio no realizaba dicho deporte, lo agrego
          if( !isset($deporteQueTenia) ){
            $deporteNuevo = new SocioDeporte;
            $deporteNuevo->idSocio = $request->id;
            $deporteNuevo->idDeporte = $deporteQueTiene;
            $deporteNuevo->save();
          }
        }
      }

      //proceso para ELIMINAR un deporte que realizaba
      //almaceno todos los deportes que realizaba y los recorro
      $deportesQueTenia = SocioDeporte::where('idSocio', $request->id)->get();
      foreach ($deportesQueTenia as $deporteQueTenia) {
        $bandera = 0;
        //si la variable está vacia, que no entre porque no tiene nada que recorrer
        if(isset($request->idDeporte)){
          //recorro los deportes que realiza ahora
          foreach ($request->idDeporte as $deporteQueTiene) {
            //si son iguales quiere decir que el que hacia lo sigue haciendo
            if($deporteQueTenia->idDeporte == $deporteQueTiene){
              $bandera = 1;
            }
          }
        }
        //en caso de que dicho deporte que realizaba no lo haga más, entra en esta condicion
        if($bandera == 0){
          SocioDeporte::destroy($deporteQueTenia->id);
        }
      }

      //retorno a la vista el socio actualizado
      return redirect()->action('SocioController@getShowId', $request->id);
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
      $socio = Socio::destroy($request->id);

      //redirijo al listado
      return redirect()->action('SocioController@getShow');
    }
}
