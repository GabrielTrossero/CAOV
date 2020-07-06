<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Persona;
use App\Mueble;
use App\Inmueble;
use App\MedioDePago;
use Illuminate\Validation\Rule;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('persona.menu');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //redirijo a la vista para agregar una persona
        return view('persona.agregar');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createFromAlquilerInmueble()
    {
      $redirect = "inmueble";

      //redirijo a la vista para agregar una persona
      return view('persona.agregar', compact('redirect'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createFromAlquilerMueble()
    {
      $redirect = "mueble";

      //redirijo a la vista para agregar una persona
      return view('persona.agregar', compact('redirect'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $persona = new Persona;

        //mensajes de error que se mostraran por pantalla
        $messages = [
          'DNI.required' => 'Es necesario ingresar un DNI válido.',
          'DNI.unique' => 'Ya existe una Persona con dicho DNI.',
          'DNI.min' => 'Es necesario ingresar un DNI válido.',
          'DNI.max' => 'Es necesario ingresar un DNI válido.',
          'nombres.required' => 'Es necesario ingresar un Nombre.',
          'nombres.max' => 'Ingrese Nombres válidos.',
          'apellido.required' => 'Es necesario ingresar un Apellido.',
          'apellido.max' => 'Ingrese un Apellido válido.',
          'domicilio.max' => 'Ingrese un Domicilio válido.',
          'telefono.max' => 'El número no puede tener más de 25 caracteres.',
          'email.unique' => 'El email no es único o válido',
          'email.max' => 'El email no es único o válido'
        ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(), [
          'DNI' => 'required|min:8|max:8|unique:persona',
          'nombres' => 'required|max:100',
          'apellido' => 'required|max:100',
          'domicilio' => 'max:100',
          'telefono' => 'max:25',
          'email' => 'unique:persona|max:75'
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withInput()->withErrors($validacion->errors());
        }

        //almaceno la persona
        $persona->create($request->all());

        $personaRetornada = Persona::where('DNI', $request->DNI)->first();

        if(isset($request->redirect)){
          $mediosDePago = MedioDePago::all();
          $personas = Persona::all();

          if($request->redirect == "mueble"){
            $muebles = Mueble::all();
            return view('alquilerMueble.agregar', compact('muebles','mediosDePago', 'personas', 'personaRetornada'));
          }
          else if($request->redirect == "inmueble"){
            $inmuebles = Inmueble::all();
            return view('alquilerInmueble.agregar', compact('inmuebles', 'mediosDePago', 'personas', 'personaRetornada'));
          }
        }

        //redirijo para mostrar la persona ingresada
        return redirect()->action('PersonaController@getShowId', $personaRetornada->id);
    }

    /**
     * Display the resource list
     *
     * @return \Illuminate\Http\Response
     */
    public function getShow()
    {
        //busco todas las personas
        $personas = Persona::all();

        //redirijo a la vista para listar todas las personas pasando el array 'personas'
        return view('persona.listado' , compact('personas'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShowId($id)
    {
        //busco la persona
        $persona = Persona::find($id);

        //redirijo a la vista individual con los datos de la persona
        return view('persona.individual' , ['persona' => $persona]);
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
        $persona = Persona::find($id);

        //redirijo al formulario de edicion con los datos de la persona
        return view('persona.editar' , ['persona' => $persona]);
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
          'DNI.required' => 'Es necesario ingresar un DNI válido.',
          'DNI.unique' => 'Ya existe una Persona con dicho DNI.',
          'DNI.min' => 'Es necesario ingresar un DNI válido.',
          'DNI.max' => 'Es necesario ingresar un DNI válido.',
          'nombres.required' => 'Es necesario ingresar un Nombre.',
          'nombres.max' => 'Ingrese Nombres válidos.',
          'apellido.required' => 'Es necesario ingresar un Apellido.',
          'apellido.max' => 'Ingrese un Apellido válido.',
          'domicilio.max' => 'Ingrese un Domicilio válido.',
          'telefono.max' => 'El número no puede tener más de 25 caracteres.',
          'email.unique' => 'El email no es único o válido',
          'email.max' => 'El email no es único o válido'
        ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(), [
          'DNI' => [
            'required',
            'min:8',
            'max:8',
            Rule::unique('persona')->ignore($request->id),
          ],
          'nombres' => 'required|max:100',
          'apellido' => 'required|max:100',
          'domicilio' => 'max:100',
          'telefono' => 'max:25',
          'email' => [
            'max:75',
            Rule::unique('persona')->ignore($request->id),
          ]
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withErrors($validacion->errors());
        }

        Persona::where('id', $request->id)
              ->update([
                'DNI' => $request->DNI,
                'nombres' => $request->nombres,
                'apellido' => $request->apellido,
                'domicilio' => $request->domicilio,
                'telefono' => $request->telefono,
                'email' => $request->email
              ]);

        //redirijo a la vista individual
        return redirect()->action('PersonaController@getShowId', $request->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //elimino el registro con tal id
        $persona = Persona::destroy($request->id);

        //redirijo al listado
        return redirect()->action('PersonaController@getShow');
    }
}
