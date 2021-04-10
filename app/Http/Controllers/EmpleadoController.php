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
        //tomo los tipos de usuarios para pasar a la vista
        $tiposUsuarios = TipoUsuario::all();

        $personas = Persona::all();

        //filtro las personas para quedarme con las que no son users
        $personas = $personas->filter(function ($value, $key) {
          if ($value->user == null) {
            return true;
          }
          else return false;
        });

        //redirijo a la vista para agregar un empleado
        return view('empleado.agregar', compact('tiposUsuarios', 'personas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //mensajes de error que se mostraran por pantalla
        $messages = [
          'username.required' => 'Es necesario ingresar nombre de usuario.',
          'username.unique' => 'Ya existe dicho nombre.',
          'username.min' => 'El nombre debe contener al menos 8 caracteres.',
          'username.max' => 'Ingrese un nombre de usuario válido.',
          'email.required' => 'Es necesario ingresar una dirección de email.',
          'email.email' => 'El Email no es único o válido.',
          'email.unique' => 'El Email no es único o válido.',
          'email.max' => 'El Email no es único o válido.',
          'idPersona.required' => 'Es necesario ingresar una Persona.',
          'password.required' => 'Es necesario ingresar una Contraseña.',
          'password.min' => 'La Contraseña debe tener como mínimo 8 caracteres.',
          'password.max' => 'Ingrese una Contraseña válida.',
          'passwordRepeat.required' => 'Es necesario repetir la Contraseña.',
          'passwordRepeat.same' => 'Las Contraseñas no coinciden.',
          'passwordRepeat.min' => 'La Contraseña debe tener como mínimo 8 caracteres.',
          'passwordRepeat.max' => 'Ingrese una Contraseña válida.',
          'idTipoUsuario.required' => 'Es necesario seleccionar un Tipo de Usuario.'
        ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(), [
          'username' => 'required|min:8|max:75|unique:users',
          'email' => 'required|email|unique:users|max:75',
          'idPersona' => 'required',
          'password' => 'required|min:8|max:80',
          'passwordRepeat' => 'required|min:8|max:80|same:password',
          'idTipoUsuario' => 'required'
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withInput()->withErrors($validacion->errors());
        }


        //obtengo la persona correspondiente
        $persona = Persona::where('id', $request->idPersona)->first();

        //valido que la persona exista
        if (!isset($persona)) {
          return redirect()->back()->withInput()->with('validarPersonaExiste', 'Error al seleccionar la Persona.');
        }
        else {
          //valido que ya no haya otro Empleado con dicho idPersona
          $socio = User::where('idPersona', $persona->id)->first();

          if(isset($socio)){
            return redirect()->back()->withInput()->with('validarSocioNoExiste', 'Error, dicho Empleado ya existe.');
          }
        }

        //valido que el tipo de usuario exista
        $tipoUsuario = TipoUsuario::find($request->idTipoUsuario);
        if (!$tipoUsuario) {
          return redirect()->back()->withInput()->with('validarTipoUsuario', 'Error al seleccionar el tipo de usuario.');
        }


        //almaceno el usuario
        $empleado = new User;
        $empleado->username = $request->username;
        $empleado->email = $request->email;
        $empleado->password = bcrypt($request->password);
        $empleado->idPersona = $persona->id;
        $empleado->idTipoUsuario = $request->idTipoUsuario;
        $empleado->remember_token = 0;
        $empleado->activo = true;

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
        //tomo todos los usuarios activos
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

        $personas = Persona::all();

        //filtro las personas para quedarme con las que no son users
        $personas = $personas->filter(function ($value, $key) {
          if ($value->user == null) {
            return true;
          }
          else return false;
        });

        //tomo los tipos de usuarios
        $tiposUsuarios = TipoUsuario::all();

        return view('empleado.editar', compact(['usuario', 'tiposUsuarios', 'personas']));
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
          'email.required' => 'Es necesario ingresar una dirección de email.',
          'email.email' => 'El Email no es único o válido.',
          'email.unique' => 'El Email no es único o válido.',
          'email.max' => 'El Email no es único o válido.',
          'idPersona.required' => 'Es necesario ingresar una Persona.',
          'password.max' => 'Ingrese una Contraseña válida.',
          'passwordRepeat.required_with' => 'Es necesario repetir la Contraseña.',
          'passwordRepeat.same' => 'La Contraseñas no coinciden.',
          'passwordRepeat.max' => 'Ingrese una Contraseña válida.',
          'idTipoUsuario.required' => 'Es necesario seleccionar un Tipo de Usuario.'
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
            'required',
            'email',
            'max:75',
            Rule::unique('users')->ignore($request->id)
          ],
          'idPersona' => 'required',
          'password' => 'max:80',
          'passwordRepeat' => 'required_with:password|max:80|same:password',
          'idTipoUsuario' => 'required'
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withInput()->withErrors($validacion->errors());
        }


        //obtengo la persona correspondiente
        $persona = Persona::where('id', $request->idPersona)->first();

        //valido que la persona exista
        if (!isset($persona)) {
          return redirect()->back()->withInput()->with('validarPersonaExiste', 'Error al seleccionar la Persona.');
        }
        else {
          //obtengo el Empleado actual (sin actualizar)
          $empleado = User::where('id', $request->id)->first();

          //valido que ya no haya otro Empleado con dicho idPersona
          $empleado = User::where('idPersona', $persona->id)
                                  ->where('id', '!=', $empleado->id)->first();

          if(isset($empleado)){
            return redirect()->back()->withInput()->with('validarEmpleadoNoExiste', 'Error, dicho Empleado ya existe.');
          }
        }

        //valido que el tipo de usuario exista
        $tipoUsuario = TipoUsuario::find($request->idTipoUsuario);
        if (!$tipoUsuario) {
          return redirect()->back()->withInput()->with('validarTipoUsuario', 'Error al seleccionar el tipo de usuario.');
        }


        User::where('id', $request->id)
              ->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'idPersona' => $persona->id,
                'idTipoUsuario' => $request->idTipoUsuario
              ]);

        return redirect()->action('EmpleadoController@getShowId', $request->id);
    }

    /**
     * Disable the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //busco el registro con tal id
        $usuario = User::find($request->id);

        //cambio atributo activo a false
        $usuario->activo = false;

        //guardo el usuario
        $usuario->save();

        //redirijo al listado
        return redirect()->action('EmpleadoController@getShow');
    }

    /**
     * Enable the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request)
    {
        //busco el registro con tal id
        $usuario = User::find($request->id);

        //cambio atributo activo a true
        $usuario->activo = true;

        //guardo el usuario
        $usuario->save();

        //redirijo al listado
        return redirect()->action('EmpleadoController@getShow');
    }
}
