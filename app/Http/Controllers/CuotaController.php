<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\MontoCuota;
use App\ComprobanteCuota;
use App\Socio;
use App\SocioComprobante;
use App\GrupoFamiliar;
use Carbon\Carbon;
use PDF;
use Mail;
use App\Mail\SendMail;

class CuotaController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //tomo todos los montoCuota y verifico que exista uno de cada tipo
    $montosCuotas = MontoCuota::all();

    $activo = false;
    $cadete = false;
    $grupoF = false;

    foreach ($montosCuotas as $montoCuota) {
      if ($montoCuota->tipo == 'a') {
        $activo = true;
      }
      elseif ($montoCuota->tipo == 'c') {
        $cadete = true;
      }
      elseif ($montoCuota->tipo == 'g') {
        $grupoF = true;
      }
    }

    return view('cuota.menu', compact('activo', 'cadete', 'grupoF'));
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
      'montoInteresGrupoFamiliar.required_if' => 'Es necesario ingresar un monto de interés.',
      'cantidadIntegrantes.required_if' => 'Es necesario ingresar la cantidad de integrantes.',
      'montoInteresMensual.required' => 'Es necesario ingresar un monto de interés mensual.',
      'cantidadMeses.required' => 'Es necesario ingresar una cantidad.'
    ];

    //valido los datos ingresados
    $validacion = Validator::make($request->all(), [
      'tipo' => 'required|in:a,c,g',
      'montoMensual' => 'required',
      'montoInteresGrupoFamiliar' => 'required_if:tipo,==,g',
      'cantidadIntegrantes' => 'required_if:tipo,==,g',
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
    $montosCuotas = MontoCuota::where('tipo', '!=', 'v')->get();

    //redirijo para mostrar el monto ingresado
    return redirect()->action('CuotaController@getShowMontoCuota');
  }



  /**
   * Display the resource list
   *
   * @return \Illuminate\Http\Response
   */
  public function getShowMontoCuota()
  {
    //MONTOS ACTUALES
    $montosActuales = new \Illuminate\Database\Eloquent\Collection; //colección donde voy a almacenar todas los montoCuotas para enviarlas a la vista

    //tomo los montocuotas actuales (uno de cada tipo)
    $monto = MontoCuota::where('tipo', 'g')->orderBy('fechaCreacion', 'DESC')->take(1)->get();//el take me obtine el primero, pero en forma d evector (a diferencia del first())
    $montosActuales = $montosActuales->merge($monto);

    $monto = MontoCuota::where('tipo', 'a')->orderBy('fechaCreacion', 'DESC')->take(1)->get();
    $montosActuales = $montosActuales->merge($monto);

    $monto = MontoCuota::where('tipo', 'c')->orderBy('fechaCreacion', 'DESC')->take(1)->get();
    $montosActuales = $montosActuales->merge($monto);


    //MONTOS HISTORICOS
    //busco todos los montos de cuotas (excepto vitalicio)
    $montosHistoricos = MontoCuota::where('tipo', '!=', 'v')->get();

    $montosHistoricos = $montosHistoricos->diff($montosActuales);

    //redirijo a la vista para listar todos los montos de cuotas pasando el array 'montoCuota'
    return view('cuota.listadoMontoCuota' , compact('montosActuales', 'montosHistoricos'));
  }



  /**
   * Shows the MontoCuota edit form
   * 
   * @param int $id
   */
  public function editMontoCuota($id) 
  {
    $montoCuota = MontoCuota::find($id);

    return view('cuota.editarMontoCuota', compact('montoCuota'));
  }



  /**
   * Updates the MontoCuota register
   *
   * @param Request $request
   * 
   * @return void
   */
  public function updateMontoCuota(Request $request)
  {
    //mensajes de error que se mostraran por pantalla
    $messages = [
      'tipo.required' => 'Es necesario ingresar un tipo.',
      'tipo.in' => 'Dicha opción no es válida.',
      'montoMensual.required' => 'Es necesario ingresar un monto mensual.',
      'montoInteresGrupoFamiliar.required_if' => 'Es necesario ingresar un monto de interés.',
      'cantidadIntegrantes.required_if' => 'Es necesario ingresar la cantidad de integrantes.',
      'montoInteresMensual.required' => 'Es necesario ingresar un monto de interés mensual.',
      'cantidadMeses.required' => 'Es necesario ingresar una cantidad.'
    ];

    //valido los datos ingresados
    $validacion = Validator::make($request->all(), [
      'tipo' => 'required|in:a,c,g',
      'montoMensual' => 'required',
      'montoInteresGrupoFamiliar' => 'required_if:tipo,==,g',
      'cantidadIntegrantes' => 'required_if:tipo,==,g',
      'montoInteresMensual' => 'required',
      'cantidadMeses' => 'required'
    ], $messages);

    //si la validacion falla vuelvo hacia atras con los errores
    if($validacion->fails()){
      return redirect()->back()->withInput()->withErrors($validacion->errors());
    }

    $montoCuota = MontoCuota::find($request->id);

    $montoCuota->tipo = $request->tipo;
    $montoCuota->montoMensual = $request->montoMensual;
    $montoCuota->montoInteresGrupoFamiliar = $request->montoInteresGrupoFamiliar;
    $montoCuota->cantidadIntegrantes = $request->cantidadIntegrantes;
    $montoCuota->montoInteresMensual = $request->montoInteresMensual;
    $montoCuota->cantidadMeses = $request->cantidadMeses;

    $montoCuota->save();

    return redirect()->action('CuotaController@getShowMontoCuota');
  }



  /**
   * Destroys a MontoCuota register passed by ID
   * 
   * @param Request $request
   */
  public function destroyMontoCuota(Request $request)
  {
    $montoCuota = MontoCuota::find($request->id);
    $cantidadCuotasAsociadasAlMonto = sizeof($montoCuota->comprobantesDeCuotas);

    if($cantidadCuotasAsociadasAlMonto > 0)
    {
      return redirect()->back()->with('montoCuotaTieneCuotas', 'El Monto de Cuota que se quiere eliminar tiene Cuotas asociadas.');
    }

    $montoCuota = MontoCuota::destroy($montoCuota->id);

    return redirect()->action('CuotaController@getShowMontoCuota');
  }



  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function showCreateCuota()
  {
    $grupoF = new GrupoFamiliarController;  //creo una instancia del controlador de GrupoFamiliar

    //elimino todos los integrantes que pasan a tener 18 este año
    $grupos = GrupoFamiliar::all();
    $integrantesEliminados = 0;

    foreach ($grupos as $grupo) {
      $integrantesEliminados += $grupoF->verificarCadetesMayores($grupo);
    }


    //una vez actualizados los integrantes, elimino los grupos que pudieron quedar con un integrante
    $grupos = GrupoFamiliar::all();
    $gruposEliminados = 0;

    foreach ($grupos as $grupo) {
      $gruposEliminados += $grupoF->verificarCantidadIntegrantes($grupo);
    }


    //recupero todas los socios
    $socios = Socio::all();

    //le agrego a cada socio el último mes pagado
    foreach ($socios as $socio) {
      $socio = $this->ultimoMesPagado($socio);
      $socio->edad = $this->calculaEdad($socio);
    }

    return view('cuota.listarSociosCreate', compact('socios', 'integrantesEliminados', 'gruposEliminados'));
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

    //le agrego al socio los montoCuota de cada categoría
    $socio = $this->asignarMontos($socio);

    //le agrego la edad
    $socio->edad = $this->calculaEdad($socio);

    //para contar la cantidad integrantes de su grupo familiar (CANTIDAD ACTUAL, es el valor que va a tener despues $cuota->cantidadIntegrantes)
    if ($socio->idGrupoFamiliar) {
      $socio->cantidadIntegrantes = Socio::where('idGrupoFamiliar', $socio->idGrupoFamiliar)->count();
    }
    else {
      $socio->cantidadIntegrantes = 0;
    }

    //retorno el socio a la vista
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
    //le agrego la edad
    $socio->edad = $this->calculaEdad($socio);

    //para redirigir si el socio quiere generar un adelanto de pago y no pagó alguna cuota anterior
    if(($socio->cuotaNoPagada) && ($request->estado == 'pagada')){
      return redirect()->back()->withInput()->with('validarPagada', 'ERROR: no puede generar un adelanto de pago si alguna cuota anterior no está pagada.');
    }

    if ($socio->ultimaCuota == null) {  //en caso de que el socio no tenga cuotas generadas
      $fechaMesAnio = Carbon::parse($socio->mesActual);  //lo pongo en formato Carbon
      $fechaPago = Carbon::parse($request->fechaPago);  //lo pongo en formato Carbon
    }
    else {  //sino tomo la cuota más actual generada y le sumo un mes
      $fechaMesAnio = Carbon::parse($socio->ultimaCuota->fechaMesAnio);  //lo pongo en formato Carbon
      $fechaMesAnio->addMonth();  //si tomo la fecha de la última cuota le agrego un mes a esta
      $fechaPago = Carbon::parse($request->fechaPago);  //lo pongo en formato Carbon
    }

    //para redirigir si la fecha de pago es mayor que el mes actual, ya que no tiene sentido poner una fecha de pago futura en un adelanto
    if (($fechaPago->month > $fechaMesAnio->month) && ($request->estado == 'pagada')) {
      return redirect()->back()->withInput()->with('validarFechaPago', 'ERROR: el mes de la fecha de pago no puede ser mayor que el mes de la cuota.');
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

    if ($socio->vitalicio == 's') {
      return redirect()->back()->withInput()->with('errorVitalicio', 'ERROR: no puede generar un adelanto de pago a un socio Vitalicio.');
    }
    elseif ($socio->idGrupoFamiliar){
      if ($socio->id != $socio->grupoFamiliar->titular) {  //para no generar una cuota a un socio no titular
        return redirect()->back()->withInput()->with('errorAdherente', 'ERROR: no puede generar un adelanto de pago a un socio que no es titular del Grupo Familiar.');
      }
      else {
        $monto = MontoCuota::select('id')->where('tipo', 'g')->orderBy('fechaCreacion', 'DESC')->first();
        $cuota->idMontoCuota = $monto['id'];
      }
    }
    elseif ($socio->edad < 18){
      $monto = MontoCuota::select('id')->where('tipo', 'c')->orderBy('fechaCreacion', 'DESC')->first();
      $cuota->idMontoCuota = $monto['id'];
    }
    else{
      $monto = MontoCuota::select('id')->where('tipo', 'a')->orderBy('fechaCreacion', 'DESC')->first();
      $cuota->idMontoCuota = $monto['id'];
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
      $cuota->fechaMesAnio = $socio->mesActual->toDateString();
    }
    else {
      //convierto la fecha porque me lo da en otro formato
      $cuota->fechaMesAnio = date("Y:m:d H:i:s", strtotime ('+1 month', strtotime ($socio->ultimaCuota->fechaMesAnio)));
    }

    $cuota->idSocio = $request->id;

    $cuota->save();

    //identifico la cuota generada
    $cuotaRetornada = ComprobanteCuota::where('idSocio', $request->id)->where('fechaMesAnio', $cuota->fechaMesAnio)->first();

    //si es de tipo grupofamiliar relaciono la cuota con los adherentes del grupo
    if ($socio->idGrupoFamiliar) {
      foreach ($socio->grupofamiliar->socios as $adherente) {
        if ($adherente->id != $socio->id) {  //para que al titular no lo ponga como adherente
          $socioComprobante = new SocioComprobante;
          $socioComprobante->idSocio = $adherente->id;
          $socioComprobante->idComprobante = $cuotaRetornada->id;
          $socioComprobante->save();
        }
      }
    }

    //si se genera la cuota como pagada, se devuelve el pdf del comprobante
    if (isset($request->fechaPago)) {
      //calculo el monto para pasarlo a la vista
      $interesPorIntegrantes = $this->montoInteresGrupoFamiliar($cuotaRetornada);
      $interesMesesAtrasados = $this->montoInteresAtraso($cuotaRetornada);
      $montoMensual = $cuotaRetornada->montoCuota->montoMensual;

      $cuotaRetornada->interesPorIntegrantes = $interesPorIntegrantes;
      $cuotaRetornada->interesMesesAtrasados = $interesMesesAtrasados;
      $cuotaRetornada->montoMensual = $montoMensual;
      $cuotaRetornada->montoTotal = $montoMensual + $interesPorIntegrantes + $interesMesesAtrasados;

      //si posee mail el socio titular, envia el mail con el detalle de la cuota pagada
      if(!is_null($cuotaRetornada->socio->persona->email)) {
        $this->enviaMailCuotaPagada($cuotaRetornada);
      }
    }

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
    $grupoF = new GrupoFamiliarController;  //creo una instancia del controlador de GrupoFamiliar

    //elimino todos los integrantes que pasan a tener 18 este año
    $grupos = GrupoFamiliar::all();
    $integrantesEliminados = 0;

    foreach ($grupos as $grupo) {
      $integrantesEliminados += $grupoF->verificarCadetesMayores($grupo);
    }


    //una vez actualizados los integrantes, elimino los grupos que pudieron quedar con un integrante
    $grupos = GrupoFamiliar::all();
    $gruposEliminados = 0;

    foreach ($grupos as $grupo) {
      $gruposEliminados += $grupoF->verificarCantidadIntegrantes($grupo);
    }


    //CUOTAS HISTORICAS
    $cuotasHistoricas = ComprobanteCuota::all(); //recupero todas las cuotas

    $cuotasHistoricas = $this->accionDeGetShow($cuotasHistoricas);


    //CUOTAS DEL MES
    //obtengo las cuotas generadas este mes
    $cuotasMes = ComprobanteCuota::whereMonth('fechaMesAnio', Carbon::now()->month)
                                  ->whereYear('fechaMesAnio', Carbon::now()->year)->get();

    $cuotasMes = $this->accionDeGetShow($cuotasMes);


    //CUOTAS IMPAGAS
    $cuotasImpagas = ComprobanteCuota::where('inhabilitada', 0)->where('fechaPago', null)->get();

    $cuotasImpagas = $this->accionDeGetShow($cuotasImpagas);


    //CUOTAS ATRASADAS
    //obtengo las cuotas que están atrasadas (todavía no se pagaron y tienen intereses de atraso)
    $cuotasAtrasadas = $cuotasImpagas;
    
    //funcion para filtrar y enviar solo las cuotas que se pagarán fuera de término
    $cuotasAtrasadas = $cuotasAtrasadas->filter(function ($value, $key) {
      if ($this->tendraInteresAtraso($value) > 0)
        return true;
      else false;
    });


    //CUOTAS INHABILITADAS
    $cuotasInhabilitadas = ComprobanteCuota::where('inhabilitada', 1)->get();

    $cuotasInhabilitadas = $this->accionDeGetShow($cuotasInhabilitadas);


    //CUOTAS PAGADAS
    $cuotasPagadas = ComprobanteCuota::where('fechaPago', '!=', null)->where('inhabilitada', 0)->get();

    $cuotasPagadas = $this->accionDeGetShow($cuotasPagadas);


    //CUOTAS PAGADA MES
    //obtengo las cuotas que fueron pagadas este mes
    $cuotasPagadasMes = ComprobanteCuota::where('fechaPago', '!=', null)
                                        ->where('inhabilitada', 0)
                                        ->whereMonth('fechaPago', Carbon::now()->month)
                                        ->whereYear('fechaPago', Carbon::now()->year)->get();

    $cuotasPagadasMes = $this->accionDeGetShow($cuotasPagadasMes);


    //CUOTAS PAGADA FUERA DE TERMINO
    $cuotasFueraDeTermino = $cuotasPagadas;

    //funcion para filtrar y enviar solo las cuotas con intereses
    $cuotasFueraDeTermino = $cuotasFueraDeTermino->filter(function ($value, $key) {
      if ($this->montoInteresAtraso($value) > 0)
        return true;
      else false;
    });


    //retorno las cuotas a la vista
    return view('cuota.listado', compact('cuotasHistoricas', 'cuotasMes', 'cuotasImpagas',
                                'cuotasAtrasadas', 'cuotasInhabilitadas', 'cuotasPagadas',
                                'cuotasPagadasMes', 'cuotasFueraDeTermino', 'integrantesEliminados',
                                'gruposEliminados'));
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
    $cuota->socio->edad = $this->calculaEdad($cuota->socio);

    //le asigno la edad a los adherentes (en caso de que la cuota sea grupofamiliar)
    if ($cuota->montoCuota->tipo == 'g'){
      foreach ($cuota->adherentes as $adherente){
        $adherente->edad = $this->calculaEdad($adherente);
        $adherente->pareja = $this->esPareja($adherente, $cuota);
      }
    }

    $cuota->montoInteresAtraso = $this->montoInteresAtraso($cuota);

    $cuota->montoInteresGrupoFamiliar = $this->montoInteresGrupoFamiliar($cuota);

    $cuota->mesesAtrasados = $this->mesesAtrasados($cuota);

    //le asigno a la cuota la cantidad de integrantes que tenía cuando se creo (adherentes + titular)
    $cuota->cantidadIntegrantes = $cuota->adherentes->count()+1;

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

    $cuota->montoInteresGrupoFamiliar = $this->montoInteresGrupoFamiliar($cuota);

    //le asigno a la cuota la cantidad de integrantes que tenía cuando se creo (adherentes + titular)
    $cuota->cantidadIntegrantes = $cuota->adherentes->count()+1;

    //se los envio a la vista
    return view('cuota.editar', ['cuota' => $cuota]);
  }



  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
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



  /**
   * fución para inhabilitar la cuota
   * @param  Request $request
   * @return \Illuminate\Http\Response
   */
  public function disable(Request $request)
  {
    ComprobanteCuota::where('id', $request->id)
          ->update([
            'inhabilitada' => true,
            'fechaPago' => null,
            'idMedioDePago' => null
          ]);

    //redirijo para mostrar la cuota actualizada
    return redirect()->action('CuotaController@getShowId', $request->id);
  }



  /**
   * fución para habilitar la cuota
   * @param  Request $request
   * @return \Illuminate\Http\Response
   */
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
   * Display the the form to add the Socios's payment.
   * @param int $id
   * @return \Illuminate\Http\Response
   */
    public function getPago($id)
    {
      //busco la cuota
      $cuota = ComprobanteCuota::find($id);

      $cuota->compruebaCuota = $this->comprobarCuota($cuota);

      $cuota->montoInteresGrupoFamiliar = $this->montoInteresGrupoFamiliar($cuota);

      //le asigno a la cuota la cantidad de integrantes que tenía cuando se creo (adherentes + titular)
      $cuota->cantidadIntegrantes = $cuota->adherentes->count()+1;

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

      //calculo el monto para pasarlo a la vista
      $interesPorIntegrantes = $this->montoInteresGrupoFamiliar($comprobanteCuota);
      $interesMesesAtrasados = $this->montoInteresAtraso($comprobanteCuota);
      $montoMensual = $comprobanteCuota->montoCuota->montoMensual;

      $comprobanteCuota->interesPorIntegrantes = $interesPorIntegrantes;
      $comprobanteCuota->interesMesesAtrasados = $interesMesesAtrasados;
      $comprobanteCuota->montoMensual = $montoMensual;
      $comprobanteCuota->montoTotal = $montoMensual + $interesPorIntegrantes + $interesMesesAtrasados;

      //si posee mail el socio titular, envia el mail con el detalle de la cuota pagada
      if(!is_null($comprobanteCuota->socio->persona->email)) {
        $this->enviaMailCuotaPagada($comprobanteCuota);
      }

      //redirijo para mostrar la cuota ingresada
      return redirect()->action('CuotaController@getShowId', $comprobanteCuota->id);
    }

    /**
     * Listar socios para ver sus cuotas
     *
     * @return \Illuminate\Http\Response
     */
    public function showSocios()
    {
      $grupoF = new GrupoFamiliarController;  //creo una instancia del controlador de GrupoFamiliar

      //elimino todos los integrantes que pasan a tener 18 este año
      $grupos = GrupoFamiliar::all();
      $integrantesEliminados = 0;

      foreach ($grupos as $grupo) {
        $integrantesEliminados += $grupoF->verificarCadetesMayores($grupo);
      }


      //una vez actualizados los integrantes, elimino los grupos que pudieron quedar con un integrante
      $grupos = GrupoFamiliar::all();
      $gruposEliminados = 0;

      foreach ($grupos as $grupo) {
        $gruposEliminados += $grupoF->verificarCantidadIntegrantes($grupo);
      }


      //recupero todas los socios
      $socios = Socio::all();

      //le agrego a cada socio el último mes pagado
      foreach ($socios as $socio) {
        $socio = $this->ultimoMesPagado($socio);
        $socio->edad = $this->calculaEdad($socio);
      }

      //retorno los socios a la vista
      return view('cuota.listarSocios', compact('socios', 'integrantesEliminados', 'gruposEliminados'));
    }



    /**
     * Listar cuotas de tal socio
     *
     * @return \Illuminate\Http\Response
     */
    public function showSocioCuotas($id)
    {
      //busco todas las cuotas de tal socio
      $c = ComprobanteCuota::where('idSocio', $id)->get();

      $cuotas = $c->filter(function ($value, $key) {  //funcion para filtrar y no eviarle las cuotas de vitalicios
          if ($value->montoCuota->tipo != 'v')
            return true;
          else false;
      });

      //busco todos los SocioComprobante (para buscar en las que está como adherente)
      $socioComprobante = SocioComprobante::where('idSocio', $id)->get();

      //dentro del foreach concateno lo que tiene $cuotas y las cuotas que voy recuperando (como adherente)
      foreach ($socioComprobante as $socCom) {
        $cuotaComoAdherente = ComprobanteCuota::where('id', $socCom->idComprobante)->get();
        $cuotas = $cuotas->merge($cuotaComoAdherente);
      }

      //le agrego a cada cuota los montos de intereses
      foreach ($cuotas as $cuota) {
        $cuota->montoInteresAtraso = $this->montoInteresAtraso($cuota);
        $cuota->montoInteresGrupoFamiliar = $this->montoInteresGrupoFamiliar($cuota);
      }

      //envío el socio para mostrar su info
      $socio = Socio::find($id);
      $socio->edad = $this->calculaEdad($socio); //le seteo la edad

      //retorno las cuotas a la vista
      return view('cuota.listarCuotasSocio', compact('cuotas', 'socio'));
    }


    /**
     * Listar cuotas de tal socio
     *
     * @return \Illuminate\Http\Response
     */
    public function generateCuotasAuto()
    {
      $grupoF = new GrupoFamiliarController;  //creo una instancia del controlador de GrupoFamiliar

      //elimino todos los integrantes que pasan a tener 18 este año
      $grupos = GrupoFamiliar::all();
      $integrantesEliminados = 0;

      foreach ($grupos as $grupo) {
        $integrantesEliminados += $grupoF->verificarCadetesMayores($grupo);
      }


      //una vez actualizados los integrantes, elimino los grupos que pudieron quedar con un integrante
      $grupos = GrupoFamiliar::all();
      $gruposEliminados = 0;

      foreach ($grupos as $grupo) {
        $gruposEliminados += $grupoF->verificarCantidadIntegrantes($grupo);
      }



      $cuotasCreadas = new \Illuminate\Database\Eloquent\Collection; //colección donde voy a almacenar todas las cuotas generadas para enviarlas a la vista

      $socios = Socio::all(); //recupero todos los socios para generarle la cuota este mes en caso que corresponda

      $fechaActual =  Carbon::now();  //obtengo la fecha actual
      $fechaActual = $fechaActual->subDays($fechaActual->day - 1);  //y le resto los días del mes, para que siempre sea el 1er dia del mes
      $fechaActual = $fechaActual->toDateString();

      foreach ($socios as $socio) {
        //le agrego la edad
        $socio->edad = $this->calculaEdad($socio);

        //busco si el socio tiene una cuota creada este mes
        $cuotaEsteMes = ComprobanteCuota::where('idSocio', $socio->id)->where('fechaMesAnio', $fechaActual)->first();

        //si el socio no tiene cuota generada para este mes y, en caso de pertenecer a un grupo familiar sea titular => le genero una cuota
        if ($cuotaEsteMes == null) {
          if ((($socio->idGrupoFamiliar) && ($socio->grupoFamiliar->titular == $socio->id)) || ($socio->idGrupoFamiliar == null)) {
            $cuota = new ComprobanteCuota; //genero la cuota
            $cuota->fechaMesAnio = $fechaActual;
            $cuota->fechaPago = null;
            $cuota->idMedioDePago = null;
            $cuota->idSocio = $socio->id;

            if (($socio->vitalicio == 's') ||($socio->activo == false)) {
              $cuota->inhabilitada = true;
            }
            else {
              $cuota->inhabilitada = false;
            }

            if ($socio->vitalicio == 's') {
              $monto = MontoCuota::select('id')->where('tipo', 'v')->orderBy('fechaCreacion', 'ASC')->first();
              $cuota->idMontoCuota = $monto['id'];

              $cuota->save();
              //las del vitalicio no se las voy a contar
            }

            elseif ($socio->idGrupoFamiliar) {
              $monto = MontoCuota::select('id')->where('tipo', 'g')->orderBy('fechaCreacion', 'DESC')->first();
              $cuota->idMontoCuota = $monto['id'];

              $cuota->save();

              //identifico la cuota generada para relacionarla con los adherentes
              $cuotaRetornada = ComprobanteCuota::where('idSocio', $socio->id)->where('fechaMesAnio', $cuota->fechaMesAnio)->first();

              //si es de tipo grupofamiliar relaciono la cuota con los adherentes del grupo
              foreach ($socio->grupofamiliar->socios as $adherente) {
                if ($adherente->id != $socio->id) {  //para que al titular no lo ponga como adherente
                  $socioComprobante = new SocioComprobante;
                  $socioComprobante->idSocio = $adherente->id;
                  $socioComprobante->idComprobante = $cuotaRetornada->id;
                  $socioComprobante->save();
                }
              }

              $cuotaRetornada = ComprobanteCuota::where('idSocio', $socio->id)->where('fechaMesAnio', $cuota->fechaMesAnio)->get(); //recupero la cuota recien generada
              $cuotasCreadas = $cuotasCreadas->merge($cuotaRetornada); //almaceno la cuota en el array a retornar
            }

            elseif ($socio->edad < 18){
              $monto = MontoCuota::select('id')->where('tipo', 'c')->orderBy('fechaCreacion', 'DESC')->first();
              $cuota->idMontoCuota = $monto['id'];

              $cuota->save();

              $cuotaRetornada = ComprobanteCuota::where('idSocio', $socio->id)->where('fechaMesAnio', $cuota->fechaMesAnio)->get(); //recupero la cuota recien generada
              $cuotasCreadas = $cuotasCreadas->merge($cuotaRetornada); //almaceno la cuota en el array a retornar
            }

            else{
              $monto = MontoCuota::select('id')->where('tipo', 'a')->orderBy('fechaCreacion', 'DESC')->first();
              $cuota->idMontoCuota = $monto['id'];

              $cuota->save();

              $cuotaRetornada = ComprobanteCuota::where('idSocio', $socio->id)->where('fechaMesAnio', $cuota->fechaMesAnio)->get(); //recupero la cuota recien generada
              $cuotasCreadas = $cuotasCreadas->merge($cuotaRetornada); //almaceno la cuota en el array a retornar
            }

          }
        }
      }



      //retorno las cuotas a la vista
      return view('cuota.listarCuotasCreadas', compact('cuotasCreadas', 'integrantesEliminados', 'gruposEliminados'));
    }



  /**
   * calcula la edad del socio ingresado por parametro
   * @param  App\Socio $socio
   * @return int
   */
  public function calculaEdad($socio)
  {
      // calcula la edad del socio segun su categoria
      $edad = Carbon::now()->year - Carbon::parse($socio->fechaNac)->year;

      //retorna la edad del socio
      return $edad;
  }



  /**
   * calcula la edad que tenía el socio al momento que se generó la cuota (si es mayor de 18 era la pareja, sino era un hijo)
   * @param  App\Socio $adherente, App\ComprobanteCuota $cuota
   * @return boolean
   */
  private function esPareja($adherente, $cuota)
  {
      // calcula la edad del socio segun su categoria
      $edad = Carbon::parse($cuota->fechaMesAnio)->year - Carbon::parse($adherente->fechaNac)->year;

      if ($edad > 18) {
        return true;
      }
      else {
        return false;
      }
  }



  /**
   * calculo el último mes pagado
   * @param  App\Socio $socio
   * @return App\Socio
   */
  private function ultimoMesPagado($socio){
    $fecha = new ComprobanteCuota;
    $fecha = ComprobanteCuota::select('fechaMesAnio')->where('idSocio', $socio->id)
                               ->where('inhabilitada', false)->orderBy('fechaMesAnio', 'DESC')
                               ->where('fechaPago', '<>', null)->first();

    $socio->fechaUltimoPago = $fecha['fechaMesAnio'];

    //compruebo si hay una cuota en la que el socio esté como adherente y sea más actual que la obtenida anteriormente
    foreach ($socio->comprobantes as $comprobante) {
      if (($comprobante->fechaMesAnio > $socio->fechaUltimoPago) && ($comprobante->fechaPago != null)) {
        $socio->fechaUltimoPago = $comprobante->fechaMesAnio;
      }
    }
    return $socio;
  }


  /**
   * busco si tal socio tiene alguna cuota sin pagar (que no sea inhabilitada)
   * @param  App\Socio $socio
   * @return App\Socio
   */
  private function hayCuotaNoPagada($socio){
    $cuota = new ComprobanteCuota;
    $cuota = ComprobanteCuota::where('idSocio', $socio->id)->where('inhabilitada', false)->where('fechaPago', null)->first();

    $socio->cuotaNoPagada = $cuota;

    return $socio;
  }


  /**
   * busco la última cuota generada de dicho socio
   * @param  App\Socio $socio
   * @return App\Socio
   */
  private function ultimoMesCuotaCreada($socio){
    $cuota = new ComprobanteCuota;
    $cuota = ComprobanteCuota::where('idSocio', $socio->id)->orderBy('fechaMesAnio', 'DESC')->first();

    $cuotasAdherente = $this->cuotasAdherentes($socio);  //busco todas las cuotas en las que está como adherente

    //en caso de que el socio sea nuevo y no tenga cuotas asignadas (tanto asignadas a él como relacionadas indirectamente como adherente), le asigno el mes actual en otra variable
    if (($cuota == null) && (empty($cuotasAdherente))) {
      $fechaActual =  Carbon::Now();  //obtengo la fecha actual
      $socio->mesActual = $fechaActual->subDays($fechaActual->day - 1);  //y le resto los días del mes, para que siempre sea el 1er dia del mes
    }

    else {
      $socio = $this->cuotasAdherentesUltimaCuota($socio);  //busco la cuota más actual que está como adherente
      if (($socio->cuotaMasActualAdherente) && ($cuota)) {  //si el socio cuota como adherente y directas
        if ($socio->cuotaMasActualAdherente->fechaMesAnio > $cuota->fechaMesAnio) {  //tomo de las dos y veo cual es más actual
          $socio->ultimaCuota = $socio->cuotaMasActualAdherente;
        }
        else {
          $socio->ultimaCuota = $cuota;
        }
      }
      elseif ($socio->cuotaMasActualAdherente){  //si solo tenía como adherente, tomo esa
        $socio->ultimaCuota = $socio->cuotaMasActualAdherente;
      }
      else {  //sino tomo la directa
        $socio->ultimaCuota = $cuota;
      }
    }

    return $socio;
  }



  /**
   * le agrego al socio los montos MÁS ACTUALES de cada categoría
   * @param  App\Socio $socio
   * @return App\Socio
   */
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



  /**
   * calculo la diferencia de meses entre el mes correspondiente y el pago
   * @param  App\ComprobanteCuota $cuota
   * @return int
   */
  private function mesesAtrasados($cuota){
    if ($cuota->fechaMesAnio < $cuota->fechaPago) {
      $date = Carbon::parse($cuota->fechaMesAnio);
      $now = Carbon::parse($cuota->fechaPago);
      return $date->diffInMonths($now);
    }
    else {
      return 0;
    }
  }



  /**
   * Calcula el monto a pagar por intereses
   *
   * @param App\ComprobanteCuota $cuota
   * @return float
   */
  public function montoInteresAtraso($cuota){
    //si la diferencia de meses entre fechaMesAnio y el pagoCuota es > que la cantidad de meses máxima permitida de atraso => se cobra intereses
    if ($this->mesesAtrasados($cuota) > $cuota->montoCuota->cantidadMeses) {
      $montoPagar = ($this->mesesAtrasados($cuota) - $cuota->montoCuota->cantidadMeses) * $cuota->montoCuota->montoInteresMensual;
      return $montoPagar;
    }
    else {
      return 0;
    }
  }

  

  /**
   * Calcula el monto a pagar por cantidad de integrantes
   *
   * @param App\ComprobanteCuota $cuota
   * @return float
   */
  public function montoInteresGrupoFamiliar($cuota){
    //para que no evalue las cuotas que no son de grupo familiar
    if ($cuota->montoCuota->tipo != 'g') {
      return 0;
    }

    //si la cantidad de integrantes registrada es > que la cantidad de integrantes mínima => cobro
    if (($cuota->adherentes->count()+1) > $cuota->montoCuota->cantidadIntegrantes) {
      $montoPagar = (($cuota->adherentes->count()+1) - $cuota->montoCuota->cantidadIntegrantes) * $cuota->montoCuota->montoInteresGrupoFamiliar;
      return $montoPagar;
    }
    else {
      return 0;
    }
  }



  /**
   * Calcula la cantidad de meses de atraso respecto a la fecha actual
   *
   * @param App\ComprobanteCuota $cuota
   * @return int
   */
  private function tendraInteresAtraso($cuota){
    //calculo la diferencia de meses entre el mesAnio y la fecha actual
    if ($cuota->fechaMesAnio < Carbon::now()) {
      $date = Carbon::parse($cuota->fechaMesAnio);
      $now = Carbon::parse($cuota->fechaPago);
      $mesesAtraso = $date->diffInMonths($now);
    }
    else {
      $mesesAtraso = 0;
    }

    //compruebo si la cantidad de meses de atraso supera la cantidad máxima de meses de atraso permitida
    if ($mesesAtraso > $cuota->montoCuota->cantidadMeses) {
      $mesesFueraDeTermino = $mesesAtraso - $cuota->montoCuota->cantidadMeses;
      return $mesesFueraDeTermino;
    }
    else {
      return 0;
    }
  }



  /**
   * comprueba que la cuota que se quiera pagar sea la "más antigua", esto es porque no tiene sentido pagar antes una más actual
   * @param  App\ComprobanteCuota $cuota
   * @return boolean
   */
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



  /**
   * retorna todas las cuotas en las que el socio está como adherente
   * @param  App\Socio $socio
   * @return App\ComprobanteCuota $cuotasAdherente
   */
  private function cuotasAdherentes($socio){
    //para capturar todas las cuotas en el que el socio está como adherente
    $cuotas = ComprobanteCuota::all();  //recupero todas las cuotas
    foreach ($cuotas as $c) {  //desagrego cada cuota
      foreach ($c->adherentes as $adherente) {  //desagrego los adherentes en cada cuota
        if ($adherente->id == $socio->id) {  //si el socio analizado está como adherente de la cuota
          $cuotasAdherente = $cuotas->filter(function ($value, $key) {  //entonces agrego la cuota en otro array
            return true;
          });
        }
      }
    }

    if (empty($cuotasAdherente)) {
      return null;
    }
    else {
      return $cuotasAdherente;
    }
  }



  /**
   * busco la cuota más actual en la que el socio está como adherente
   * @param  App\Socio $socio
   * @return App\Socio $socio
   */
  private function cuotasAdherentesUltimaCuota($socio){
    $cuotaMasActual = new ComprobanteCuota;
    $cuotaMasActual->fechaMesAnio = '1900-01-01';  //le pongo una fecha exagerada para que guarde la primera vez
    //para capturar todas las cuotas en el que el socio está como adherente
    $cuotas = ComprobanteCuota::all();  //recupero todas las cuotas
    foreach ($cuotas as $c) {  //desagrego cada cuota
      foreach ($c->adherentes as $adherente) {  //desagrego los adherentes en cada cuota
        if ($adherente->id == $socio->id) {  //si el socio analizado está como adherente de la cuota
          if ($cuotaMasActual->fechaMesAnio < $c->fechaMesAnio) {  //si se cumple guardo la cuota más actual
            $cuotaMasActual = $c;
          }
        }
      }
    }

    if ($cuotaMasActual->fechaMesAnio != '1900-0101') {
      $socio->cuotaMasActualAdherente = $cuotaMasActual;
    }
    else {
      $socio->cuotaMasActualAdherente = null;
    }

    return $socio;
  }



  /**
   * envia mail con el detalle de la cuota pagada
   *
   * @param App\ComprobanteCuota $cuotaPagada
   *
   * @return void
   */
  public function enviaMailCuotaPagada($cuotaPagada) {
    $numSocio = $cuotaPagada->idSocio;

    $arrayCuota = array(
      'emailTo' => $cuotaPagada->socio->persona->email,
      'apellido_nombres' => $cuotaPagada->socio->persona->apellido.", ".$cuotaPagada->socio->persona->nombres,
      'numSocio' => $numSocio,
      'fechaMesAnio' => $cuotaPagada->fechaMesAnio,
      'fechaPago' => $cuotaPagada->fechaPago,
      'montoMensual' => $cuotaPagada->montoMensual,
      'interesPorIntegrantes' => $cuotaPagada->interesPorIntegrantes,
      'interesMesesAtrasados' => $cuotaPagada->interesMesesAtrasados,
      'montoTotal' => $cuotaPagada->montoTotal
    );

    Mail::to($arrayCuota['emailTo'])->send(new SendMail($arrayCuota, 'cuota'));
  }



  /**
     * genera el pdf para el id de la cuota pagada dada
     *
     * @param Request $request
     *
     * @return PDF
     */
    public function generarPdfCuota($id) {
      //tomo la reserva pagada
      $comprobanteCuota = ComprobanteCuota::find($id);

      $interesPorIntegrantes = $this->montoInteresGrupoFamiliar($comprobanteCuota);
      $interesMesesAtrasados = $this->montoInteresAtraso($comprobanteCuota);
      $montoMensual = $comprobanteCuota->montoCuota->montoMensual;

      $comprobanteCuota->interesPorIntegrantes = $interesPorIntegrantes;
      $comprobanteCuota->interesMesesAtrasados = $interesMesesAtrasados;
      $comprobanteCuota->montoMensual = $montoMensual;
      $comprobanteCuota->montoTotal = $montoMensual + $interesPorIntegrantes + $interesMesesAtrasados;

      $pdf = PDF::loadView('pdf.comprobantes.cuota', ['comprobante' => $comprobanteCuota]);

      return $pdf->download('comprobante-cuota.pdf');
    }



  /**
   * funcion que se utiliza en GetShow()
   * @param  App\Cuota $cuotas
   * @return App\Cuota $cuotas
   */
  private function accionDeGetShow($cuotas){
    //le agrego a cada cuota los montos que se usan en la columna "Monto Total"
    foreach ($cuotas as $cuota) {
      $cuota->montoInteresAtraso = $this->montoInteresAtraso($cuota);
      $cuota->montoInteresGrupoFamiliar = $this->montoInteresGrupoFamiliar($cuota);
    }

    $cuotas = $cuotas->filter(function ($value, $key) {  //funcion para filtrar y no eviarle las cuotas de vitalicios
        if ($value->montoCuota->tipo != 'v')
          return true;
        else false;
    });

    return $cuotas;
  }
}
