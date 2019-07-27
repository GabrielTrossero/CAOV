<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\MovExtras;
use Illuminate\Support\Facades\Validator;

class RegistroController extends Controller
{
  /**
   * Display the form to add a Registro.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //tomo los usuarios
    $usuarios = User::all();

    //devuelvo la vista para agregar un registro
    return view('registro.agregar', compact('usuarios'));
  }

  /**
   * Add the Registro.
   * @param Request $request
   *
   * @return \Illuminate\Http\Response
   */
  public function postRegistro(Request $request)
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
      'tipoRegistro.in' => 'Ingrese un Tipo de Registro válido',
      'usuario.required' => 'Es necesario un Usuario'
    ];

    //valido los datos ingresados
    $validacion = Validator::make($request->all(),[
    'numRecibo' => 'required|max:11',
    'fecha' => 'required',
    'monto' => 'required|regex:/^[1-9][0-9]+/|not_in:0',
    'descripcion' => 'required|max:100',
    'tipoRegistro' => 'required|in:1,2',
    'usuario' => 'required'
    ], $messages);

    //si la validación falla vuelvo hacia atras con los errores
    if($validacion->fails()){
      return redirect()->back()->withInput()->withErrors($validacion->errors());
    }

    $movimiento = new MovExtras;

    //almaceno el movimiento extra
    $movimiento->numRecibo = $request->numRecibo;
    $movimiento->tipo = $request->tipoRegistro;
    $movimiento->descripcion = $request->descripcion;
    $movimiento->fecha = $request->fecha;
    $movimiento->monto = $request->monto;
    $movimiento->idUser = $request->usuario;

    $movimiento->save();

    //redirijo al formulario para agregar nuevos registros, con mensaje de exito incluido
    return redirect()->back()->with('success', 'Registro creado con éxito!');
  }
}
