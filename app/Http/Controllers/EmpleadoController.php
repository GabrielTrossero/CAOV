<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Persona;
use App\TipoUsuario;
use Illuminate\Validation\Rule;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('empleado.menu');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //tomo datos de las personas para pasar a la vista
        $personas = Persona::select('id', 'DNI', 'apellido', 'nombres')->get();

        //tomo los tipos de usuarios para pasar a la vista
        $tiposUsuarios = TipoUsuario::all();

        //redirijo a la vista para agregar un empleado
        return view('empleado.agregar', compact(['personas', 'tiposUsuarios']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empleado = new User;

        //mensajes de error que se mostraran por pantalla
        $messages = [
          'username.required' => 'Es necesario ingresar nombre de usuario.',
          'username.unique' => 'Ya existe dicho nombre.',
          'username.min' => 'El nombre debe contener al menos 8 caracteres.',
          'username.max' => 'Ingrese un nombre de usuario válido.',
          'email.email' => 'El Email no es único o válido.',
          'email.unique' => 'El Email no es único o válido.',
          'email.max' => 'El Email no es único o válido.',
          'persona.required' => 'Es necesario seleccionar una Persona.',
          'password.required' => 'Es necesario ingresar una Contraseña.',
          'password.min' => 'La Contraseña debe tener como mínimo 8 caracteres.',
          'password.max' => 'Ingrese una Contraseña válida.',
          'passwordRepeat.required' => 'Es necesario repetir la Contraseña.',
          'passwordRepeat.same' => 'La Contraseñas no coinciden.',
          'passwordRepeat.min' => 'La Contraseña debe tener como mínimo 8 caracteres.',
          'passwordRepeat.max' => 'Ingrese una Contraseña válida.',
          'tipoUsuario.required' => 'Es necesario seleccionar un Tipo de Usuario.'
        ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(), [
          'username' => 'required|min:8|max:75|unique:users',
          'email' => 'email|unique:users|max:75',
          'persona' => 'required',
          'password' => 'required|min:8|max:80',
          'passwordRepeat' => 'required|min:8|max:80|same:password',
          'tipoUsuario' => 'required'
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withInput()->withErrors($validacion->errors());
        }

        //almaceno el usuario
        $empleado->username = $request->username;
        $empleado->email = $request->email;
        $empleado->password = bcrypt($request->password);
        $empleado->idPersona = $request->persona;
        $empleado->idTipoUsuario = $request->tipoUsuario;

        $empleado->save();

        $empleadoRetornado = User::where('username', $request->username)->first();

        //redirijo para mostrar el usuario ingresado
        return redirect()->action('EmpleadoController@getShowId', $empleadoRetornado->id);
    }

    /**
     * Display the resource list
     *
     * @return \Illuminate\Http\Response
     */
    public function getShow()
    {
        //tomo todos los usuarios
        $usuarios = User::all();

        //redirijo al listado de usuarios
        return view('empleado.listado', compact('usuarios'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShowId($id)
    {
        //tomo el usuario
        $usuario = User::find($id);

        //retorno la vista del usuario
        return view('empleado.individual', ['usuario' => $usuario]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //tomo el usuario
        $usuario = User::find($id);

        //tomo las personas
        $personas = Persona::select('id', 'DNI', 'apellido', 'nombres')->get();

        //tomo los tipos de usuarios
        $tiposUsuarios = TipoUsuario::all();

        return view('empleado.editar', compact(['usuario' , 'personas', 'tiposUsuarios']));
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
          'username.required' => 'Es necesario ingresar nombre de usuario.',
          'username.unique' => 'Ya existe dicho nombre.',
          'username.min' => 'El nombre debe contener al menos 8 caracteres.',
          'username.max' => 'Ingrese un nombre de usuario válido.',
          'email.email' => 'El Email no es único o válido.',
          'email.unique' => 'El Email no es único o válido.',
          'email.max' => 'El Email no es único o válido.',
          'persona.required' => 'Es necesario seleccionar una Persona.',
          'persona.required' => 'Es necesario seleccionar una Persona.',
          'password.max' => 'Ingrese una Contraseña válida.',
          'passwordRepeat.required_with' => 'Es necesario repetir la Contraseña.',
          'passwordRepeat.same' => 'La Contraseñas no coinciden.',
          'passwordRepeat.max' => 'Ingrese una Contraseña válida.',
          'tipoUsuario.required' => 'Es necesario seleccionar un Tipo de Usuario.'
        ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(), [
          'username' => [
            'required',
            'min:8',
            'max:75',
            Rule::unique('users')->ignore($request->id)
          ],
          'email' => [
            'email',
            'max:75',
            Rule::unique('users')->ignore($request->id)
          ],
          'persona' => 'required',
          'password' => 'max:80',
          'passwordRepeat' => 'required_with:password|max:80|same:password',
          'tipoUsuario' => 'required'
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withInput()->withErrors($validacion->errors());
        }

        User::where('id', $request->id)
              ->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'idPersona' => $request->persona,
                'idTipoUsuario' => $request->tipoUsuario
              ]);

        return redirect()->action('EmpleadoController@getShowId', $request->id);
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
        $usuario = User::destroy($request->id);

        //redirijo al listado
        return redirect()->action('EmpleadoController@getShow');
    }
}
