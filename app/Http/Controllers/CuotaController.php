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
      'montoMensual.required' => 'Es necesario ingresar un monto mensual.',
      'montoInteresMensual.required' => 'Es necesario ingresar un monto mensual.',
      'cantidadMeses.required' => 'Es necesario ingresar una cantidad.'
    ];

    //valido los datos ingresados
    $validacion = Validator::make($request->all(), [
      'tipo' => 'required|in:a,c,g',
      'montoMensual' => 'required',
      'montoInteresMensual' => 'required',
      'cantidadMeses' => 'required'
    ], $messages);

    //si la validacion falla vuelvo hacia atras con los errores
    if($validacion->fails()){
      return redirect()->back()->withInput()->withErrors($validacion->errors());
    }

    //almaceno el monto de cuota
    $montoCuota->create($request->all());

    //recupero todos los montos de cuotas para mostrarlos en la vista
    $montosCuotas = MontoCuota::all();

    //redirijo para mostrar el monto ingresado
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
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function showCreateCuota()
  {
    //recupero todas los socios
    $socios = Socio::all();

    //le agrego a cada socio el último mes pagado
    foreach ($socios as $socio) {
      $socio = $this->ultimoMesPagado($socio);
    }

    //retorno los socios a la vista
    return view('cuota.listarSociosCreate', compact('socios'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function createCuota($id)
  {
    //recupero el socio
    $socio = Socio::find($id);

    //le agrego al socio la última cuota que tiene generada
    $socio = $this->ultimoMesCuotaCreada($socio);

    //para saber si no pagó alguna cuota
    $socio = $this->hayCuotaNoPagada($socio);

    //le agrego al socio los montoCuota de cada categoría
    $socio = $this->asignarMontos($socio);

    //para contar la cantidad integrantes de su grupo familiar (CANTIDAD ACTUAL, es el valor que va a tener despues $cuota->cantidadIntegrantes)
    if ($socio->idGrupoFamiliar) {
      $socio->cantidadIntegrantes = Socio::where('idGrupoFamiliar', $socio->idGrupoFamiliar)->count();
    }
    else {
      $socio->cantidadIntegrantes = 0;
    }

    //retorno los socios a la vista
    return view('cuota.agregarCuota', compact('socio'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function storeCuota(Request $request)
  {
    $cuota = new ComprobanteCuota;
    $socio = new Socio;
    $socio = Socio::find($request->id);

    //le agrego al socio la última cuota que tiene generada
    $socio = $this->ultimoMesCuotaCreada($socio);
    //para saber si no pagó alguna cuota
    $socio = $this->hayCuotaNoPagada($socio);

    //para redirigir si el socio quiere generar un adelanto de pago y no pagó alguna cuota anterior
    if(($socio->cuotaNoPagada) && ($request->estado == 'pagada')){
      return redirect()->back()->withInput()->with('validarPagada', 'ERROR: no puede generar un adelanto de pago si alguna cuota anterior no está pagada.');
    }


    //mensajes de error que se mostraran por pantalla
    $messages = [
      'estado.required' => 'Es necesario ingresar un estado.',
      'estado.in' => 'Dicha opción no es válida.',
      'fechaPago.required_if' => 'Es necesario ingresar una fecha de pago.',
      'medioPago.required_if' => 'Es necesario ingresar un medio de pago.'
    ];

    //valido los datos ingresados
    $validacion = Validator::make($request->all(), [
      'estado' => 'required|in:pagada,inhabilitada',
      'fechaPago' => 'required_if:estado,==,pagada',
      'medioPago' => 'required_if:estado,==,pagada',
    ], $messages);

    //si la validacion falla vuelvo hacia atras con los errores
    if($validacion->fails()){
      return redirect()->back()->withInput()->withErrors($validacion->errors());
    }

    if ($socio->idGrupoFamiliar){
      $monto = MontoCuota::select('id')->where('tipo', 'g')->orderBy('fechaCreacion', 'DESC')->first();
      $cuota->idMontoCuota = $monto['id'];
      $cuota->cantidadIntegrantes = Socio::where('idGrupoFamiliar', $socio->idGrupoFamiliar)->count();
    }
    elseif ($socio->idGrupoFamiliar){
                 //COMPLETAR #####################################
      $monto = MontoCuota::select('id')->where('tipo', 'c')->orderBy('fechaCreacion', 'DESC')->first();
      $cuota->idMontoCuota = $monto['id'];
      $cuota->cantidadIntegrantes = 0;
    }
    else{
      $monto = MontoCuota::select('id')->where('tipo', 'a')->orderBy('fechaCreacion', 'DESC')->first();
      $cuota->idMontoCuota = $monto['id'];
      $cuota->cantidadIntegrantes = 0;
    }


    if ($request->estado == 'inhabilitada'){
      $cuota->inhabilitada = true;
      $cuota->fechaPago = null;
      $cuota->idMedioDePago = null;
    }
    else{
      $cuota->inhabilitada = false;
      $cuota->fechaPago = $request->fechaPago;
      $cuota->idMedioDePago = $request->medioPago;
    }

    //en caso de que el socio sea nuevo va a tener null, entonces le pongo el mes corriente
    if ($socio->ultimaCuota == null) {
      $cuota->fechaMesAnio = $socio->mesActual;
    }
    else {
      //convierto la fecha porque me lo da en otro formato
      $cuota->fechaMesAnio = date("Y:m:d H:i:s", strtotime ('+1 month', strtotime ($socio->ultimaCuota->fechaMesAnio)));
    }

    $cuota->idSocio = $request->id;

    $cuota->save();

    $cuotaRetornada = ComprobanteCuota::where('idSocio', $request->id)->where('fechaMesAnio', $cuota->fechaMesAnio)->first();

    //redirijo para mostrar la cuota ingresada
    return redirect()->action('CuotaController@getShowId', $cuotaRetornada->id);
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

    //le agrego a cada cuota los montos que se usan en la columna "Monto Total"
    foreach ($cuotas as $cuota) {
      $cuota->montoInteresAtrazo = $this->montoInteresAtrazo($cuota);
      $cuota->montoInteresGrupoFamiliar = $this->montoInteresGrupoFamiliar($cuota);
    }

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
    $cuota->socio->edad = Carbon::parse($cuota->socio->fechaNac)->age; //ELIMINAR

    $cuota->mesesAtrazados = $this->mesesAtrazados($cuota);
    $cuota->montoInteresAtrazo = $this->montoInteresAtrazo($cuota);

    $cuota->montoInteresGrupoFamiliar = $this->montoInteresGrupoFamiliar($cuota);

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

    //le asigno a la cuota el MontoCuota de cada tipo de socio
    $cuota = $this->montoTipoSocio($cuota); //NO LO USO

    $cuota->mesesAtrazados = $this->mesesAtrazados($cuota);
    $cuota->montoInteresAtrazo = $this->montoInteresAtrazo($cuota);

    $cuota->montoInteresGrupoFamiliar = $this->montoInteresGrupoFamiliar($cuota);

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
    //busco la cuota que estoy actualizando
    $comprobanteCuota = ComprobanteCuota::find($request->id);

    //para redirigir si el socio quiere editar una cuota inhabilitada
    if($comprobanteCuota->inhabilitada == true){
      return redirect()->back()->withInput()->with('errorInhabilitada', 'ERROR: no puede editar una cuota que se encuentra inhabilitada.');
    }
    //para redirigir si el socio quiere editar una cuota que no está pagada
    else if($comprobanteCuota->fechaPago == null){
      return redirect()->back()->withInput()->with('errorNoPagada', 'ERROR: no puede editar una cuota que NO está pagada.');
    }

    //mensajes de error que se mostraran por pantalla
    $messages = [
      'fechaPago.required_if' => 'Es necesario ingresar una Fecha de Pago.',
      'medioPago.required_if' => 'Es necesario ingresar un Medio de Pago.',
      'medioPago.in' => 'El Medio de Pago ingresado es incorrecto.',
      'pagada.required' => 'Es necesario especificar si la Cuota está pagada.',
      'pagada.in' => 'El campo es incorrecto'
    ];

    //valido los datos ingresados
    $validacion = Validator::make($request->all(), [
      'fechaPago' => 'required_if:pagada,==,s',
      'medioPago' => 'required_if:pagada,==,s|in:1',
      'pagada' => 'required|in:s,n'
    ], $messages);

    //si la validacion falla vuelvo hacia atras con los errores
    if($validacion->fails()){
      return redirect()->back()->withInput()->withErrors($validacion->errors());
    }

    //dependiendo de si "pagada" es "s" o "n", actualizo la cuota
    if ($request->pagada == 's') {
      $comprobanteCuota->fechaPago = $request->fechaPago;
      $comprobanteCuota->idMedioDePago = $request->medioPago;
    }
    elseif ($request->pagada == 'n') {
      $comprobanteCuota->fechaPago = null;
      $comprobanteCuota->idMedioDePago = null;
    }

    $comprobanteCuota->save();

    //redirijo para mostrar la cuota actualizada
    return redirect()->action('CuotaController@getShowId', $request->id);
  }

  //inhabilitar cuota
  public function disable(Request $request)
  {
    ComprobanteCuota::where('id', $request->id)
          ->update([
            'inhabilitada' => true
          ]);

    //redirijo para mostrar la cuota actualizada
    return redirect()->action('CuotaController@getShowId', $request->id);
  }

  //habilitar cuota
  public function enable(Request $request)
  {
    ComprobanteCuota::where('id', $request->id)
          ->update([
            'inhabilitada' => false
          ]);

    //redirijo para mostrar la cuota actualizada
    return redirect()->action('CuotaController@getShowId', $request->id);
  }


  /**
   * Display the list of Socios to choose who paids the Cuota.
   *
   * @return \Illuminate\Http\Response
   */
  public function getShowPago()
  {
    //obtengo todos los socios
    $socios = Socio::all();

    foreach ($socios as $socio) {
      $socio = $this->calculaUltimoMes($socio);
    }

    //se los envio a la vista
    return view('cuota.listarSociosPago', compact('socios'));
  }

/**
   * Display the the form to add the Socios's payment.
   * @param int $id
   * @return \Illuminate\Http\Response
   */
    public function getPago($id)
    {
      //busco la cuota
      $cuota = ComprobanteCuota::find($id);

      $cuota->mesesAtrazados = $this->mesesAtrazados($cuota);
      $cuota->montoInteresAtrazo = $this->montoInteresAtrazo($cuota);

      $cuota->compruebaCuota = $this->comprobarCuota($cuota);

      $cuota->montoInteresGrupoFamiliar = $this->montoInteresGrupoFamiliar($cuota);

      //se lo envío a la vista
      return view('cuota.ingresarPago', ['cuota' => $cuota]);
    }


  /**
  * Add the payment and generate a pdf or send it via email.
  * @param Request $request
   *
   * @return \Illuminate\Http\Response
   */
    public function postPago(Request $request)
    {
      //busco la cuota que estoy cargando
      $comprobanteCuota = ComprobanteCuota::find($request->id);

      //para redirigir si el socio quiere pagar una cuota inhabilitada
      if($comprobanteCuota->inhabilitada == true){
        return redirect()->back()->withInput()->with('errorInhabilitada', 'ERROR: no puede pagar una cuota que se encuentra inhabilitada.');
      }
      //para redirigir si el socio quiere pagar una cuota que ya está pagada
      else if($comprobanteCuota->fechaPago != null){
        return redirect()->back()->withInput()->with('errorPagada', 'ERROR: no puede pagar una cuota que ya está pagada.');
      }


      //mensajes de error que se mostraran por pantalla
      $messages = [
        'fechaPago.required' => 'Es necesario ingresar una Fecha de Pago.',
        'medioPago.required' => 'Es necesario ingresar un Medio de Pago.',
        'medioPago.in' => 'El Medio de Pago ingresado es incorrecto.'
      ];

      //valido los datos ingresados
      $validacion = Validator::make($request->all(), [
        'fechaPago' => 'required',
        'medioPago' => 'required|in:1'
      ], $messages);

      //si la validacion falla vuelvo hacia atras con los errores
      if($validacion->fails()){
        return redirect()->back()->withInput()->withErrors($validacion->errors());
      }

      //cargo los datos de ComprobanteCuota
      $comprobanteCuota->fechaPago = $request->fechaPago;
      $comprobanteCuota->idMedioDePago = $request->medioPago;

      $comprobanteCuota->save();

      //redirijo para mostrar la cuota ingresada
      return redirect()->action('CuotaController@getShowId', $comprobanteCuota->id);
    }


  /**
   * calcula la edad del socio ingresado por parametro
   * @param  App\Socio $socio
   * @return App\Socio
   */
  private function calculaEdad($socio)
  {
      /*retorno la edad del socio
      return Carbon::parse($socio->fechaNac)->age;
      */

      // calcula la edad del socio segun su categoria
      $edad = Carbon::now()->year - Carbon::parse($socio->fechaNac)->year;

      //retorna la edad del socio
      return $edad;
  }


  /**
   * calcula la edad del socio ingresado por parametro
   * @param  App\Socio $socio
   * @return App\Socio
   */
  private function calculaUltimoMes($socio)
  {
      //busco el último mes que pagó tal socio
      $ultimoMes = ComprobanteCuota::select('fechaMesAnio')->where('idSocio', $socio->id)->orderBy('fechaMesAnio', 'DESC')->first();

      //si ha pagado cuotas, asigno al atributo ultimoMesPagado lo que recuperé anteriormente
      if($ultimoMes){
        $socio->ultimoMesPagado = $ultimoMes->fechaMesAnio;
      }
      //sino le asigno null
      else{
        $socio->ultimoMesPagado = null;
      }

      //retorna al socio con su último mes pagado
      return $socio;
  }


  //calculo el último mes pagado
  private function ultimoMesPagado($socio){
    $fecha = new ComprobanteCuota;
    $fecha = ComprobanteCuota::select('fechaMesAnio')->where('idSocio', $socio->id)->where('inhabilitada', false)->orderBy('fechaMesAnio', 'DESC')->first();

    $socio->fechaUltimoPago = $fecha['fechaMesAnio'];

    return $socio;
  }

  //busco si tal socio tiene alguna cuota sin pagar (que no sea inhabilitada)
  private function hayCuotaNoPagada($socio){
    $cuota = new ComprobanteCuota;
    $cuota = ComprobanteCuota::where('idSocio', $socio->id)->where('inhabilitada', false)->where('fechaPago', null)->first();

    $socio->cuotaNoPagada = $cuota;

    return $socio;
  }

  //busco la última cuota generada de dicho socio
  private function ultimoMesCuotaCreada($socio){
    $cuota = new ComprobanteCuota;
    $cuota = ComprobanteCuota::where('idSocio', $socio->id)->orderBy('fechaMesAnio', 'DESC')->first();

    //en caso de que el socio sea nuevo y no tenga cuotas asignadas, le asigno el mes actual en otra variable
    if ($cuota == null) {
      $socio->mesActual = Carbon::Now();
    }

    $socio->ultimaCuota = $cuota;

    return $socio;
  }

  //le agrego al socio los montos MÁS ACTUALES de cada categoría
  private function asignarMontos($socio){

    $monto = MontoCuota::select('montoMensual')->where('tipo', 'a')->orderBy('fechaCreacion', 'DESC')->first();
    $socio->montoActivo = $monto['montoMensual'];

    $monto = MontoCuota::where('tipo', 'g')->orderBy('fechaCreacion', 'DESC')->first();
    $socio->montoGrupoFamiliar = $monto['montoMensual'];
    $socio->montoCuotaInteresGrupoFamiliar = $monto['montoInteresGrupoFamiliar'];
    $socio->montoCuotaCantidadIntegrantes = $monto['cantidadIntegrantes'];

    $monto = MontoCuota::select('montoMensual')->where('tipo', 'c')->orderBy('fechaCreacion', 'DESC')->first();
    $socio->montoCadete = $monto['montoMensual'];

    return $socio;
  }

  //calculo la diferencia de meses entre el mes correspondiente y el pago
  private function mesesAtrazados($cuota){
    if ($cuota->fechaMesAnio < $cuota->fechaPago) {
      $date = Carbon::parse($cuota->fechaMesAnio);
      $now = Carbon::parse($cuota->fechaPago);
      return $date->diffInMonths($now);
    }
    else {
      return 0;
    }
  }

  //calculo el monto a pagar por intereses
  private function montoInteresAtrazo($cuota){
    //si la diferencia de meses entre fechaMesAnio y el pagoCuota es > que la cantidad de meses máxima permitida de atrazo => se cobra intereses
    if ($this->mesesAtrazados($cuota) > $cuota->montoCuota->cantidadMeses) {
      $montoPagar = ($this->mesesAtrazados($cuota) - $cuota->montoCuota->cantidadMeses) * $cuota->montoCuota->montoInteresMensual;
      return $montoPagar;
    }
    else {
      return 0;
    }
  }
/*
  private function integrantesGrupoFamiliar($cuota){
    if ($cuota->montoCuota->tipo == 'g') {
      $idGrupoFamiliar = $cuota->socio->grupoFamiliar->id;
      $cantidadIntegrantes = Socio::where('idGrupoFamiliar', $idGrupoFamiliar)->count();
    }
    else {
      $cantidadIntegrantes = '0';
    }

    return $cantidadIntegrantes;
  }*/

  //calculo el monto a pagar por cantidad de integrantes
  private function montoInteresGrupoFamiliar($cuota){
    //para que no evalue las cuotas que no son de grupo familiar
    if ($cuota->montoCuota->tipo != 'g') {
      return 0;
    }

    //si la cantidad de integrantes registrada es > que la cantidad de integrantes mínima => cobro
    if ($cuota->cantidadIntegrantes > $cuota->montoCuota->cantidadIntegrantes) {
      $montoPagar = ($cuota->cantidadIntegrantes - $cuota->montoCuota->cantidadIntegrantes) * $cuota->montoCuota->montoInteresGrupoFamiliar;
      return $montoPagar;
    }
    else {
      return 0;
    }
  }

//NO LO USO ##########
  //le asigno cada uno de los montos (activo, grupo familiar, cadete) correspondientes al montoCuota DE LA FECHA
  private function montoTipoSocio($cuota){
    //a la fechaMesAnio le sumo un mes (para que abarque todo ese mes) porque ser que coincida con el mes de creacion del montoCuota
    $fecha = date("Y:m:d H:i:s", strtotime ('+1 month', strtotime ($cuota->fechaMesAnio)));

    //le asigno cada uno de los montos
    $cuota->montoActivo = MontoCuota::where('tipo', 'a')->where('fechaCreacion','<', $fecha)->orderBy('fechaCreacion','DES')->first();

    $cuota->montoGrupoFamiliar = MontoCuota::where('tipo', 'g')->where('fechaCreacion','<', $fecha)->orderBy('fechaCreacion','DES')->first();

    $cuota->montoCadete = MontoCuota::where('tipo', 'c')->where('fechaCreacion','<', $fecha)->orderBy('fechaCreacion','DES')->first();

    return $cuota;
  }

  //comprueba que la cuota que se quiera pagar sea la "más antigua", esto es por como está diseñado el sistema de pagos
  private function comprobarCuota($cuota){
    //obtengo la cuota más vieja
    $cuotaQueDeberiaPagar = ComprobanteCuota::where('idSocio', $cuota->idSocio)->where('fechaPago', null)->where('inhabilitada', false)->orderBy('fechaMesAnio', 'ASC')->first();

    //si la que estoy por pagar es igual a la que debería pagar (la más vieja), entonces está bien
    if ($cuota->id == $cuotaQueDeberiaPagar->id) {
      return true;
    }
    else {
      return false;
    }
  }

}
