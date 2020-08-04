<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\MovExtras;
use App\ReservaMueble;
use App\ReservaInmueble;
use Illuminate\Support\Facades\Validator;

class RegistroController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      return view('registro.menu');
  }


  /**
   * Display the form to add a Registro.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //tomo los usuarios
    $usuarios = User::all();

    //devuelvo la vista para agregar un registro
    return view('registro.agregar');
  }

  /**
   * Add the Registro.
   * @param Request $request
   *
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //determino los mensajes de error de validación
    $messages = [
      'numRecibo.required' => 'Es necesario ingresar un Número de Recibo.',
      'numRecibo.max' => 'Ingrese un Numero de Recibo válido',
      'fecha.required' => 'Es necesario ingresar una Fecha',
      'monto.required' => 'Es necesario ingresa un Monto',
      'monto.regex' => 'Es necesario ingresar un Monto positivo mayor a 0 (cero)',
      'descripcion.required' => 'Es necesario ingresar una Descripcion',
      'descripcion.max' => 'Ingrese una Descripcion de menos de 100 caracteres',
      'tipoRegistro.required' => 'Es necesario un Tipo de Registro',
      'tipoRegistro.in' => 'Ingrese un Tipo de Registro válido'
    ];

    //valido los datos ingresados
    $validacion = Validator::make($request->all(),[
    'numRecibo' => 'required|max:11',
    'fecha' => 'required',
    'monto' => 'required|regex:/^[1-9][0-9]+/|not_in:0',
    'descripcion' => 'required|max:100',
    'tipoRegistro' => 'required|in:1,2'
    ], $messages);

    //si la validación falla vuelvo hacia atras con los errores
    if($validacion->fails()){
      return redirect()->back()->withInput()->withErrors($validacion->errors());
    }


    //valido que el Usuario exista
    $validarUsuario = User::where('id', $request->id)->first();

    if (!isset($validarUsuario)) {
      return redirect()->back()->withInput()->with('validarUsuario', 'Error en el Usuario.');
    }


    //compruebo que el numRecibo no se repita
    $alquileresMueble = ReservaMueble::all();
    $alquileresInmueble = ReservaInmueble::all();
    $registros = MovExtras::all();

    foreach ($alquileresMueble as $alquilerMueble) {
      if ($alquilerMueble->numRecibo == $request->numRecibo) {
        return redirect()->back()->withInput()->with('validarNumRecibo', 'Error, dicho Número de Recibo ha sido usado en un Alquiler de Mueble.');
      }
    }

    foreach ($alquileresInmueble as $alquilerInmueble) {
      if ($alquilerInmueble->numRecibo == $request->numRecibo) {
        return redirect()->back()->withInput()->with('validarNumRecibo', 'Error, dicho Número de Recibo ha sido usado en un Alquiler de Inmueble.');
      }
    }

    foreach ($registros as $registro) {
      if ($registro->numRecibo == $request->numRecibo) {
        return redirect()->back()->withInput()->with('validarNumRecibo', 'Error, dicho Número de Recibo ha sido usado en otro Registro.');
      }
    }


    $movimiento = new MovExtras;

    //almaceno el movimiento extra
    $movimiento->numRecibo = $request->numRecibo;
    $movimiento->tipo = $request->tipoRegistro;
    $movimiento->descripcion = $request->descripcion;
    $movimiento->fecha = $request->fecha;
    $movimiento->monto = $request->monto;
    $movimiento->idUser = $request->id;

    $movimiento->save();

    //redirijo al formulario para agregar nuevos registros, con mensaje de exito incluido
    return redirect()->back()->with('success', 'Registro creado con éxito!');
  }


  /**
   * Display the resource list
   *
   * @return \Illuminate\Http\Response
   */
  public function getShow()
  {
      //tomo todos los registros
      $movimientos = MovExtras::all();

      //los envio a la vista
      return view('registro.listado', compact('movimientos'));
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    //compruebo que el registro exista
    $movimiento = MovExtras::find($request->id);
    if (!$movimiento) {
      return redirect()->back()->withInput()->with('validarEliminar', 'ERROR: seleccione un Registro válido.');
    }

    //elimino el registro con tal id
    MovExtras::destroy($request->id);

    //compruebo que el registro se haya eliminado
    $movimiento = MovExtras::find($request->id);
    if ($movimiento) {
      return redirect()->back()->withInput()->with('validarEliminar', 'ERROR: el Registro no se eliminó.');
    }

    //redirijo al listado
    return redirect()->action('RegistroController@getShow');
  }
}
