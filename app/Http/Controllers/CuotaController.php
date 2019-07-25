<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\MontoCuota;
use App\ComprobanteCuota;
use App\Socio;
use Carbon\Carbon;

class CuotaController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('cuota.menu');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function createMontoCuota()
  {
    //redirijo a la vista para agregar un monto de cuota
    return view('cuota.agregarMontoCuota');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function storeMontoCuota(Request $request)
  {
    $montoCuota = new MontoCuota;

    //mensajes de error que se mostraran por pantalla
    $messages = [
      'tipo.required' => 'Es necesario ingresar un tipo.',
      'tipo.in' => 'Dicha opción no es válida.',
      'dtoSemestre.required' => 'Es necesario ingresar un descuento semestral.',
      'dtoAnio.required' => 'Es necesario ingresar una descuento anual.',
      'monto.required' => 'Es necesario ingresar un monto.'
    ];

    //valido los datos ingresados
    $validacion = Validator::make($request->all(), [
      'tipo' => 'required|in:a,c,g',
      'dtoSemestre' => 'required',
      'dtoAnio' => 'required',
      'monto' => 'required'
    ], $messages);

    //si la validacion falla vuelvo hacia atras con los errores
    if($validacion->fails()){
      return redirect()->back()->withInput()->withErrors($validacion->errors());
    }

    //almaceno el monto de cuota
    $montoCuota->create($request->all());

    //recupero todos los montos de cuotas para mostrarlos en la vista
    $montosCuotas = MontoCuota::all();

    //redirijo para mostrar la persona ingresada
    return view('cuota.listadoMontoCuota' , compact('montosCuotas'));
  }

  /**
   * Display the resource list
   *
   * @return \Illuminate\Http\Response
   */
  public function getShowMontoCuota()
  {
    //busco todos los montos de cuotas
    $montosCuotas = MontoCuota::all();

    //redirijo a la vista para listar todos los montos de cuotas pasando el array 'montoCuota'
    return view('cuota.listadoMontoCuota' , compact('montosCuotas'));
  }

  /**
   * Display the resource list
   *
   * @return \Illuminate\Http\Response
   */
  public function getShow()
  {
    //recupero todas las cuotas
    $cuotas = ComprobanteCuota::all();

    //retorno las cuotas a la vista
    return view('cuota.listado', compact('cuotas'));
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function getShowId($id)
  {
    //busco la cuota
    $cuota = ComprobanteCuota::find($id);

    //calculo la edad para despues mostrarlo en la vista
    $cuota->socio->edad = Carbon::parse($cuota->socio->fechaNac)->age;

    //se lo envío a la vista
    return view('cuota.individual', ['cuota' => $cuota]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //recupero la cuota a editar
    $cuota = ComprobanteCuota::find($id);

    //se los envio a la vista
    return view('cuota.editar', ['cuota' => $cuota]);
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
      'fechaPago.required' => 'Es necesario ingresar una Fecha de Pago.',
      'fechaMesAnio.required' => 'Es necesario ingresar un Mes y Año correspondinte.',
      'medioPago.required' => 'Es necesario ingresar un Medio de Pago.',
      'medioPago.in' => 'El Medio de Pago ingresado es incorrecto.',
      'tipo.required' => 'Es necesario ingresar un Tipo.',
      'tipo.in' => 'El Tipo ingresado es incorrecto.'
    ];

    //valido los datos ingresados
    $validacion = Validator::make($request->all(), [
      'fechaPago' => 'required',
      'fechaMesAnio' => 'required',
      'medioPago' => 'required|in:1',
      'tipo' => 'required|in:m,s,a'
    ], $messages);

    //si la validacion falla vuelvo hacia atras con los errores
    if($validacion->fails()){
      return redirect()->back()->withInput()->withErrors($validacion->errors());
    }

    //actualizo el ComprobanteCuota correspondinte
    ComprobanteCuota::where('id', $request->id)
          ->update([
            'tipo' => $request->tipo,
            'fechaMesAnio' => $request->fechaMesAnio,
            'fechaPago' => $request->fechaPago,
            'idMedioDePago' => $request->medioPago
          ]);

    //redirijo para mostrar la cuota actualizada
    return redirect()->action('CuotaController@getShowId', $request->id);
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
    $cuota = ComprobanteCuota::destroy($request->id);

    //redirijo al listado
    return redirect()->action('CuotaController@getShow');
  }
}
