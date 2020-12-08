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
use Carbon\Carbon;

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
        $grupos = GrupoFamiliar::all();

        $deportes = Deporte::all();

        $personas = Persona::all();

        //filtro las personas para quedarme con las que no son socio
        $personas = $personas->filter(function ($value, $key) {
          if ($value->socio == null) {
            return true;
          }
          else return false;
        });

        //obtengo el último socio agregado para mostrar el siguiente numSocio recomendado
        $socioMasReciente = Socio::orderBy('fechaCreacion', 'DESC')->first();

        //los envio a la vista del formulario
        return view('socio.agregar', compact(['grupos','deportes', 'personas', 'socioMasReciente']));
    }

    /**
     * Devuelve el formulario de Agregar viniendo desde PersonaController
     *
     * @param int $id
     * 
     * @return void
     */
    public function createFromPersona($id)
    {
      $personaRetornada = Persona::find($id);

      $grupos = GrupoFamiliar::all();
      
      $deportes = Deporte::all();
      
      $personas = Persona::all();
      
      $personas = $personas->filter(function ($value, $key) {
        if ($value->socio == null) {
          return true;
        }
        else return false;
      });

      //obtengo el último socio agregado para mostrar el siguiente numSocio recomendado
      $socioMasReciente = Socio::orderBy('fechaCreacion', 'DESC')->first();
      
      return view('socio.agregar', compact('grupos', 'deportes', 'personas', 'personaRetornada', 'socioMasReciente'));
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
          'numSocio.required' => 'Es necesario ingresar un Número de Socio.',
          'numSocio.unique' => 'Ya existe un Socio con dicho número.',
          'fechaNac.required' => 'Es necesario ingresar la Fecha de Nacimiento.',
          'oficio.max' => 'Ingrese un oficio válido.',
          'vitalicio.required' => 'Ingrese una opción válida.',
          'vitalicio.min' => 'Ingrese una opción válida.',
          'vitalicio.max' => 'Ingrese una opción válida.',
          'vitalicio.in' => 'Ingrese una opción válida.',
          'idPersona.required' => 'Es necesario ingresar una Persona.',
          'idGrupoFamiliar.required_if' => 'Es necesario ingresar una opción.',
        ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(),[
        'numSocio' => 'required|unique:socio',
        'fechaNac' => 'required',
        'oficio' => 'max:75',
        'vitalicio' => 'required|min:1|max:1|in:s,n',
        'idPersona' => 'required',
        'idGrupoFamiliar' => 'required_if:vitalicio,==,n'
        ], $messages);

        //si la validacion falla vuelvo hacia atras con los errores
        if($validacion->fails()){
          return redirect()->back()->withInput()->withErrors($validacion->errors());
        }


        //valido que el id del Grupo Familiar exista
        $validarGrupoFamiliar = GrupoFamiliar::where('id', $request->idGrupoFamiliar)->first();

        if ($request->vitalicio == 's') {
          $request->idGrupoFamiliar = null;
        }
        elseif($request->vitalicio == 'n'){
              if($request->idGrupoFamiliar == 0){
                $request->idGrupoFamiliar = null;
              }
              elseif (!isset($validarGrupoFamiliar)) {
                return redirect()->back()->withInput()->with('validarGrupoFamiliar', 'Error al seleccionar un Grupo Familiar');
              }
              else {  //solo puedo agragar cadetes al grupo desde acá, asi que compruebo que sea cadete
                $edad = Carbon::now()->year - Carbon::parse($request->fechaNac)->year;
                if ($edad >= 18) {
                  return redirect()->back()->withInput()->with('validarGrupoFamiliar', 'Solo se pueden agregar Cadetes desde esta pestaña. Para agregar una persona mayor a un grupo, dirigirse a Grupo Familiar luego de generar dicho Socio.');
                }
              }
        }



        //obtengo la persona correspondiente
        $persona = Persona::where('id', $request->idPersona)->first();

        //valido que la persona exista
        if (!isset($persona)) {
          return redirect()->back()->withInput()->with('validarPersonaExiste', 'Error al seleccionar la Persona.');
        }
        else {
          //valido que ya no haya otro Socio con dicho idPersona
          $socio = Socio::where('idPersona', $persona->id)->first();

          if(isset($socio)){
            return redirect()->back()->withInput()->with('validarSocioNoExiste', 'Error, dicho Socio ya existe.');
          }
        }


        //almaceno al socio y el deporte que realiza
        $socio = new Socio;
        $socio->numSocio = $request->numSocio;
        $socio->fechaNac = $request->fechaNac;
        $socio->oficio = $request->oficio;
        $socio->vitalicio = $request->vitalicio;
        $socio->idPersona = $persona->id;
        $socio->idGrupoFamiliar = $request->idGrupoFamiliar;

        $socio->save();

        //cargo los deportes que realiza, si es que tiene
        if(isset($request->idDeporte)){
          $socio = Socio::where('numSocio', $request->numSocio)->first();

          foreach ($request->idDeporte as $value) {
            //valido que el id del Deporte exista
            $validarDeporte = Deporte::where('id', $value)->first();
            if (!isset($validarDeporte)) {
              return redirect()->back()->withInput()->with('validarDeporte', 'Error al seleccionar un Deporte');
            }

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
     * calcula la edad del socio ingresado por parametro
     * @param  App\Socio $socio
     * @return App\Socio
     */
    private function calculaEdad($socio)
    {
        //asigna al atributo edad del socio su edad calculada a partir de su fecha de nacimiento
        $socio->edad = Carbon::now()->year - Carbon::parse($socio->fechaNac)->year;

        //retorna al socio con su edad
        return $socio;
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

        //le agrego a cada socio su edad
        foreach ($socios as $socio) {
          $socio = $this->calculaEdad($socio);
        }

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

        //calculo la edad del socio
        $socio = $this->calculaEdad($socio);

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

        $personas = Persona::all();

        //filtro las personas para quedarme con las que no son socio
        $personas = $personas->filter(function ($value, $key) {
          if ($value->socio == null) {
            return true;
          }
          else return false;
        });

        //almaceno todos los grupos familiares
        $grupos = GrupoFamiliar::all();

        //almaceno todos deportes
        $deportes = Deporte::all();

        //almaceno SocioDeporte
        $socioDeporte = SocioDeporte::all();

        //se lo envío a la vista
        return view('socio.editar', compact(['socio', 'personas', 'grupos','deportes','socioDeporte']));
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
        'fechaNac.required' => 'Es necesario ingresar la Fecha de Nacimiento.',
        'oficio.max' => 'Ingrese un oficio válido.',
        'vitalicio.required' => 'Ingrese una opción válida.',
        'vitalicio.min' => 'Ingrese una opción válida.',
        'vitalicio.max' => 'Ingrese una opción válida.',
        'vitalicio.in' => 'Ingrese una opción válida.',
        'idPersona.required' => 'Es necesario ingresar una Persona.',
        'idGrupoFamiliar.required_if' => 'Es necesario ingresar una opción.',
        'activo.required' => 'Es necesario ingresar el Estado del Socio.',
        'activo.in' => 'Es necesario ingresar valores válidos para el Estado del Socio.'
      ];

      //valido los datos ingresados
      $validacion = Validator::make($request->all(),[
      'numSocio' => [
        'required',
        Rule::unique('socio')->ignore($request->id)
      ],
      'oficio' => 'max:75',
      'vitalicio' => 'required|min:1|max:1|in:s,n',
      'idPersona' => 'required',
      'idGrupoFamiliar' => 'required_if:vitalicio,==,n',
      'activo' => 'required|in:0,1'
      ], $messages);

      //si la validacion falla vuelvo hacia atras con los errores
      if($validacion->fails()){
        return redirect()->back()->withInput()->withErrors($validacion->errors());
      }


      //valido que el id del Grupo Familiar exista
      $validarGrupoFamiliar = GrupoFamiliar::where('id', $request->idGrupoFamiliar)->first();

      if ($request->vitalicio == 's') {
        $request->idGrupoFamiliar = null;
      }
      elseif($request->vitalicio == 'n'){
            if($request->idGrupoFamiliar == 0){
              $request->idGrupoFamiliar = null;
            }
            elseif (!isset($validarGrupoFamiliar)) {
              return redirect()->back()->withInput()->with('validarGrupoFamiliar', 'Error al seleccionar un Grupo Familiar');
            }
            else {
              $socio = Socio::where('id', $request->id)->first(); //obtengo el Socio actual (sin actualizar)

              if ($request->idGrupoFamiliar != $socio->idGrupoFamiliar) {  //si se quiere cambiar de grupo
                //solo puedo agragar cadetes al grupo desde acá, asi que compruebo que sea cadete
                $edad = Carbon::now()->year - Carbon::parse($request->fechaNac)->year;
                if ($edad >= 18) {
                  return redirect()->back()->withInput()->with('validarGrupoFamiliar', 'Solo se pueden agregar Cadetes desde esta pestaña. Para agregar una persona mayor a un grupo, dirigirse a Grupo Familiar.');
                }
              }
            }
      }


      //obtengo la persona correspondiente
      $persona = Persona::where('id', $request->idPersona)->first();

      //valido que la persona exista
      if (!isset($persona)) {
        return redirect()->back()->withInput()->with('validarPersonaExiste', 'Error al seleccionar la Persona.');
      }
      else {
        //obtengo el Socio actual (sin actualizar)
        $socio = Socio::where('id', $request->id)->first();

        //valido que ya no haya otro Socio con dicho idPersona
        $socio = Socio::where('idPersona', $persona->id)
                        ->where('id', '!=', $socio->id)->first();

        if(isset($socio)){
          return redirect()->back()->withInput()->with('validarSocioNoExiste', 'Error, dicho Socio ya existe.');
        }
      }

      //obtengo el socio correspondiente
      $socio = Socio::where('id', $request->id)->first();

      //si se decide que el socio no tiene grupo
      if (is_null($request->idGrupoFamiliar)) {
        //si el socio es titular redirijo con error
        if(isset($socio->idGrupoFamiliar) && ($socio->grupoFamiliar->titular == $socio->id)) {
          return redirect()->back()->withInput()->with('esSocioTitular', 'Error, dicho Socio es el titular del grupo familiar. Para eliminarlo, edite su condición en el mismo.');
        }

        //si el socio tiene grupo y es pareja, setea el atributo pareja del grupo a null
        if (isset($socio->idGrupoFamiliar) && ($socio->grupoFamiliar->pareja == $socio->id)) {
          $grupo = GrupoFamiliar::find($socio->idGrupoFamiliar);
          $grupo->pareja = null;
          $grupo->save();
        }

        //si el socio no es el titular, se asigna null al atributo idGrupoFamiliar
        if (isset($socio->idGrupoFamiliar) && ($socio->id != $socio->grupoFamiliar->titular)) {
          $socio->idGrupoFamiliar = null;
        }
      }

      if (($request->idGrupoFamiliar != 0) && ($request->activo == 0) && ($socio->idGrupoFamiliar)) {
        return redirect()->back()->withInput()->with('validarSocioParaInactivo', 'Para poner el socio como Inactivo, también debe eliminarlo del Grupo Familiar.');
      }
      elseif (($request->idGrupoFamiliar != 0) && ($request->activo == 0) && (!$socio->idGrupoFamiliar)) {
        return redirect()->back()->withInput()->with('validarSocioParaInactivo2', 'Para agregar el socio a un grupo familiar, también debe estar activo.');
      }


      //si el socio se pone como activo, pongo en null la fechaBaja
      if ($request->activo == 1) {
        $request->fechaBaja = null;
      } 
      else { //si se pone como inactivo, pongo como fechaBaja la actual
        $request->fechaBaja = Carbon::now();
      }


      Socio::where('id', $request->id)
            ->update([
              'fechaNac' => $request->fechaNac,
              'idGrupoFamiliar' => $request->idGrupoFamiliar,
              'idPersona' => $persona->id,
              'numSocio' => $request->numSocio,
              'oficio' => $request->oficio,
              'vitalicio' => $request->vitalicio,
              'activo' => $request->activo,
              'fechaBaja' => $request->fechaBaja
            ]);


      //proceso para AGREGAR un nuevo deporte a dicho socio
      //si está vacía la variable que contiene los deportes que ahora realiza, que no entre
      if(isset($request->idDeporte)){
        foreach ($request->idDeporte as $deporteQueTiene) {
          //valido que el id del Deporte exista
          $validarDeporte = Deporte::where('id', $deporteQueTiene)->first();
          if (!isset($validarDeporte)) {
            return redirect()->back()->withInput()->with('validarDeporte', 'Error al seleccionar un Deporte');
          }

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
     * @param  Illuminate\Http\Request $request
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
