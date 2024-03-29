<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\GrupoFamiliar;
use App\Socio;
use App\Traits\compruebaCadete;
use Carbon\Carbon;

class GrupoFamiliarController extends Controller
{
    //Importación de la clase compruebaCadete de Traits
    use compruebaCadete;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('grupoFamiliar.menu');
    }

       /**
     * Actualiza grupo familiar, eliminando grupos o borrando los integrantes activos
     *
     * @param App\GrupoFamiliar $grupo
     * @return int
     */
    public function verificarCadetesMayores($grupo)
    {
      //veo si hay socios que son cadetes y pasan a ser mayores este año
      $socios = $grupo->socios;
      $eliminados = 0;

      foreach ($socios as $socio) {
        if ((!$this->isCadete($socio->fechaNac)) && ($socio->id != $grupo->titular) && ($socio->id != $grupo->pareja)) {
          $socio->idGrupoFamiliar = null;
          $socio->save();

          $eliminados += 1;
        }
      }

      return $eliminados;
    }


    public function verificarCantidadIntegrantes($grupo)
    {
      //veo si el grupo tiene menos de 2 integrantes para eliminarlo
      if (sizeof($grupo->socios) < 2) {
        $socio = Socio::find($grupo->titular);
        $socio->idGrupoFamiliar = null;
        $socio->save();

        $grupo = GrupoFamiliar::destroy($grupo->id);

        return 1;
      }

      return 0;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      //elimino todos los integrantes que pasan a tener 18 este año
      $grupos = GrupoFamiliar::all();
      $integrantesEliminados = 0;

      foreach ($grupos as $grupo) {
        $integrantesEliminados += $this->verificarCadetesMayores($grupo);
      }


      //una vez actualizados los integrantes, elimino los grupos que pudieron quedar con un integrante
      $grupos = GrupoFamiliar::all();
      $gruposEliminados = 0;

      foreach ($grupos as $grupo) {
        $gruposEliminados += $this->verificarCantidadIntegrantes($grupo);
      }


      //tomo los socios
      $socios = Socio::where('idGrupoFamiliar', null)->where('vitalicio', 'n')->get();

      //filtro los socios mayores de edad
      $sociosMayores = $socios->filter(function ($socio){
        return !$this->isCadete($socio->fechaNac);
      });

      $sociosMenores = $socios->filter(function ($socio){
        return $this->isCadete($socio->fechaNac);
      });

      return view('grupoFamiliar.agregar', compact('sociosMenores', 'sociosMayores', 'integrantesEliminados', 'gruposEliminados'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $grupo = new GrupoFamiliar;

      $messages = [
        'titular.required' => 'Es necesario ingresar un Socio Titular.',
        'titular.unique' => 'El Titular ya pertenece a otro Grupo Familiar.',
        'pareja.unique' => 'La Pareja ya pertenece a otro Grupo Familiar.'
      ];

      //valido los datos ingresados
      $validacion = Validator::make($request->all(),[
        'titular' => 'required|unique:grupofamiliar',
        'pareja' => 'unique:grupofamiliar'
      ], $messages);

      //si la validacion falla vuelvo hacia atras con los errores
      if($validacion->fails())
      {
        return redirect()->back()->withInput()->withErrors($validacion->errors());
      }

      //valido si los socios titular y pareja son distintos
      if($request->titular == $request->pareja)
      {
        return redirect()->back()->withInput()->with('error', 'Los socios Titular y Pareja son la misma Persona, por favor revisar la selección.');
      }

      $titular = Socio::find($request->titular);
      $pareja = Socio::find($request->pareja);

      //valido que el titular y la pareja (si es que ingresó pareja) están como socios
      if ((!$titular) || (($request->pareja != 0) && (!$pareja))) {
        return redirect()->back()->withInput()->with('error', 'Error al seleccionar el Titular o la Pareja.');
      }

      //verifico que el titular no esté como pareja de otro grupo
      if (GrupoFamiliar::where('pareja', $titular->id)->first()) {
        return redirect()->back()->withInput()->with('error', 'El Titular ya pertenece a otro Grupo Familiar.');
      }

      //verifico que la pareja no esté como pareja de otro grupo
      if ($request->pareja) {
        if (GrupoFamiliar::where('titular', $pareja->id)->first()) {
          return redirect()->back()->withInput()->with('errorPareja', 'La Pareja ya pertenece a otro Grupo Familiar.');
        }
      }
      
      //verifico que los miembros no pertenezcan a otro grupo familiar
      if ($request->miembros) {
        foreach ($request->miembros as $miembro) {
          $socio = Socio::find($miembro);
          
          //valido que sean menores de edad
          if(!$this->isCadete($socio->fechaNac)){
            return redirect()->back()->withInput()->with('errorAdherente', 'Los Adherentes (cadetes) deben ser menores de edad.');
          }
          if ($socio->idGrupoFamiliar) {
            return redirect()->back()->withInput()->with('errorAdherente', 'Alguno de los miembros ya pertenece a un Grupo Familiar.');
          }
          if ($socio->activo == 0) {
            return redirect()->back()->withInput()->with('cadeteInactivo', 'Uno o más cadetes que se intentan agregar se encuentran inactivos.');
          }
        }
      }

      //valido si los socios titular y pareja son mayores de edad
      if(($this->isCadete($titular->fechaNac)) || (isset($pareja) && ($this->isCadete($pareja->fechaNac))))
      {
        return redirect()->back()->withInput()->with('error', 'Los socios Titular y Pareja deben ser mayores de edad');
      }

      //valido que el titular y la pareja estén como activos (los cadetes ya se controlaron)
      if ($titular->activo == 0) {
        return redirect()->back()->withInput()->with('titularInactivo', 'El socio Titular que intenta agregar se encuentra inactivo.');
      }
      elseif (($pareja != null) && ($pareja->activo == 0)) {
        return redirect()->back()->withInput()->with('parejaInactivo', 'La Pareja que intenta agregar se encuentra inactivo.');
      }


      //almaceno el grupo familiar en la BD
      $grupo = new GrupoFamiliar;
      $grupo->titular = $request->titular;

      //guardo el id del socio pareja en el grupo
      if ($request->pareja != 0) {
        $grupo->pareja = $request->pareja;
      }
      else {
        $grupo->pareja = NULL;
      }

      $grupo->save();


      //tomo el grupo para pasarlo a la vista individual del mismo
      $grupoRetornado = GrupoFamiliar::where('titular', $request->titular)->first();

      //le guardo al grupo que pertenecen
      $titular->idGrupoFamiliar = $grupoRetornado->id;
      $titular->save();
      if ($request->pareja != 0){
        $pareja->idGrupoFamiliar = $grupoRetornado->id;
        $pareja->save();
      }

      if ($request->miembros) {
        foreach ($request->miembros as $miembro) {
          $nuevoMiembro = Socio::find($miembro);
          $nuevoMiembro->idGrupoFamiliar = $grupoRetornado->id;
          $nuevoMiembro->save();
        } 
      }


      //redirijo a la vista individual del grupo
      return redirect()->action('GrupoFamiliarController@getShowId', $grupoRetornado->id);
    }

    /**
     * Display the resource list
     *
     * @return \Illuminate\Http\Response
     */
    public function getShow()
    {
      //elimino todos los integrantes que pasan a tener 18 este año
      $grupos = GrupoFamiliar::all();
      $integrantesEliminados = 0;

      foreach ($grupos as $grupo) {
        $integrantesEliminados += $this->verificarCadetesMayores($grupo);
      }


      //una vez actualizados los integrantes, elimino los grupos que pudieron quedar con un integrante
      $grupos = GrupoFamiliar::all();
      $gruposEliminados = 0;

      foreach ($grupos as $grupo) {
        $gruposEliminados += $this->verificarCantidadIntegrantes($grupo);
      }

      //tomo los grupos familiares actualizados
      $grupos = GrupoFamiliar::all();

      return view('grupoFamiliar.listado', compact('grupos', 'integrantesEliminados', 'gruposEliminados'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShowId($id)
    {
        //tomo el grupo familiar
        $grupo = GrupoFamiliar::find($id);

        //retorno la vista individual del grupo familiar
        return view('grupoFamiliar.individual', compact('grupo'));
    }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
  */
  public function editTitular($id)
  {
      //tomo el grupo familiar
      $grupo = GrupoFamiliar::find($id);

      //tomo los socios sin grupo familiar
      $sociosSinGrupo = Socio::where('idGrupoFamiliar', null)->where('vitalicio', 'n')->get();

      //tomo socios mayores de edad sin grupo para posible titular
      $sociosTitular = $sociosSinGrupo->filter(function ($socio){
        return !$this->isCadete($socio->fechaNac);
      });


      //redirijo a la vista de edicion del grupo familiar
      return view('grupoFamiliar.editarTitular', compact('grupo', 'sociosTitular'));
  }

/**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\Response
   */
  public function updateTitular(Request $request)
  {
      $messages = [
        'titular.required' => 'Es necesario ingresar un Socio Titular.',
        'titular.unique' => 'El Titular ya pertenece a otro Grupo Familiar.'
        ];

      //valido los datos ingresados
      $validacion = Validator::make($request->all(),[
        'titular' => [
          'required',
          Rule::unique('grupofamiliar')->ignore($request->id),
          ]
      ], $messages);

      //si la validacion falla vuelvo hacia atras con los errores
      if($validacion->fails())
      {
        return redirect()->back()->withInput()->withErrors($validacion->errors());
      }

      $titular = Socio::find($request->titular);

      //valido que el titular está como socio
      if (!$titular) {
        return redirect()->back()->withInput()->with('error', 'Error al seleccionar el Titular.');
      }

      //verifico que el titular no esté como pareja de otro grupo
      if (GrupoFamiliar::where('pareja', $titular->id)->where('id', '!=', $request->id)->first()) {
        return redirect()->back()->withInput()->with('error', 'El Titular ya pertenece a otro Grupo Familiar.');
      }

      //valido si es mayor de edad
      if($this->isCadete($titular->fechaNac))
      {
        return redirect()->back()->withInput()->with('error', 'El Titular debe ser mayor de edad');
      }

      //valido que el titular esté activo
      if ($titular->activo == 0) {
        return redirect()->back()->withInput()->with('titularInactivo', 'El socio Titular que intenta agregar se encuentra inactivo.');
      }

      //tomo el grupo a actualizar
      $grupo = GrupoFamiliar::find($request->id);

      //si el numero de titular es distinto al titular actual, se actualiza el titular del grupo
      if ($titular->id != $grupo->titular){
        $titularViejo = Socio::find($grupo->titular);
        $titularViejo->idGrupoFamiliar = null;
        $titularViejo->save();
        $grupo->titular = $titular->id;
        $titular->idGrupoFamiliar = $grupo->id;
        $titular->save();
        $grupo->save();
        $grupo->refresh();
      }

      //redirijo a la vista individual del grupo
      return redirect()->action('GrupoFamiliarController@getShowId', $grupo->id);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
  */
  public function editPareja($id)
  {
      //tomo el grupo familiar
      $grupo = GrupoFamiliar::find($id);

      //tomo los socios sin grupo familiar
      $sociosSinGrupo = Socio::where('idGrupoFamiliar', null)->where('vitalicio', 'n')->get();

      //tomo socios mayores de edad sin grupo para posible pareja
      $sociosPareja = $sociosSinGrupo->filter(function ($socio){
        return !$this->isCadete($socio->fechaNac);
      });


      //redirijo a la vista
      return view('grupoFamiliar.editarPareja', compact('grupo', 'sociosPareja'));
  }

/**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\Response
   */
  public function updatePareja(Request $request)
  {
      $messages = [
        'pareja.unique' => 'La Pareja ya pertenece a otro Grupo Familiar.'
        ];

      //valido los datos ingresados
      $validacion = Validator::make($request->all(),[
        'pareja' => [
          Rule::unique('grupofamiliar')->ignore($request->id)
          ]
      ], $messages);

      //si la validacion falla vuelvo hacia atras con los errores
      if($validacion->fails())
      {
        return redirect()->back()->withInput()->withErrors($validacion->errors());
      }

      //si igresó pareja
      if ($request->pareja != 0) {
        $pareja = Socio::find($request->pareja);

        //valido que la pareja está como socio
        if (!$pareja) {
          return redirect()->back()->withInput()->with('error', 'Error al seleccionar la Pareja.');
        }

        //verifico que la pareja no esté como titular de otro grupo
        if ($request->pareja) {
          if (GrupoFamiliar::where('titular', $pareja->id)->where('id', '!=', $request->id)->first()) {
            return redirect()->back()->withInput()->with('error', 'La Pareja ya pertenece a otro Grupo Familiar.');
          }
        }

        //valido si es mayor de edad
        if($this->isCadete($pareja->fechaNac))
        {
          return redirect()->back()->withInput()->with('error', 'La Pareja debe ser mayor de edad.');
        }

        //valido que la pareja esté activa
        if ($pareja->activo == 0) {
          return redirect()->back()->withInput()->with('parejaInactivo', 'La pareja que intenta agregar se encuentra inactiva.');
        }

        //tomo el grupo a actualizar
        $grupo = GrupoFamiliar::find($request->id);

        //si el numero de pareja es distinto a la pareja actual y antes tenía pareja, se actualiza
        if (($pareja->id != $grupo->pareja)&&($grupo->pareja != 0)){
          $parejaViejo = Socio::find($grupo->pareja);
          $parejaViejo->idGrupoFamiliar = null;
          $parejaViejo->save();
          $grupo->pareja = $pareja->id;
          $pareja->idGrupoFamiliar = $grupo->id;
          $pareja->save();
          $grupo->save();
          $grupo->refresh();
        }

        //si el numero de pareja es distinto a la pareja actual y antes NO tenía pareja, se actualiza
        elseif (($pareja->id != $grupo->pareja) && ($grupo->pareja == 0)){
          $grupo->pareja = $pareja->id;
          $pareja->idGrupoFamiliar = $grupo->id;
          $pareja->save();
          $grupo->save();
          $grupo->refresh();
        }
      }

      //si no ingresó pareja
      else {
        //tomo el grupo a actualizar
        $grupo = GrupoFamiliar::find($request->id);

        // valido si el grupo posee menos de 2 miembros
        if((sizeof($grupo->socios) < 3) && ($grupo->pareja))
        {
          return redirect()->back()->withInput()->with('error', 'No se puede eliminar el integrante, el Grupo Familiar quedaría con un solo miembro.');
        }

        //si el numero de pareja es distinto a la pareja actual, se actualiza
        if ($grupo->pareja != 0){
          $parejaViejo = Socio::find($grupo->pareja);
          $parejaViejo->idGrupoFamiliar = null;
          $parejaViejo->save();
          $grupo->pareja = null;
          $grupo->save();
          $grupo->refresh();
        }
      }
      

      //redirijo a la vista individual del grupo
      return redirect()->action('GrupoFamiliarController@getShowId', $grupo->id);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
  */
  public function addMenor($id)
  {
      //tomo el grupo familiar
      $grupo = GrupoFamiliar::find($id);

      //tomo los socios sin grupo familiar
      $sociosSinGrupo = Socio::where('idGrupoFamiliar', null)->where('vitalicio', 'n')->get();

      //filtros los socios sin grupo familiar menores de edad
      $sociosMenores = $sociosSinGrupo->filter(function ($socio){
        return $this->isCadete($socio->fechaNac);
      });


      //redirijo a la vista
      return view('grupoFamiliar.agregarMenor', compact('grupo', 'sociosMenores'));
  }

/**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\Response
   */
  public function storeMenor(Request $request)
  {
      $messages = [
        'menores.required' => 'Es necesario ingresar un Socio.',
        ];

      //valido los datos ingresados
      $validacion = Validator::make($request->all(),[
        'menores' => 'required'
      ], $messages);

      //si la validacion falla vuelvo hacia atras con los errores
      if($validacion->fails())
      {
        return redirect()->back()->withInput()->withErrors($validacion->errors());
      }

      //verifico los miembros
      if ($request->menores) {
        foreach ($request->menores as $miembro) {
          //tomo el socio que se agrega al grupo
          $socio = Socio::find($miembro);

          //valido que el titular esté como socio
          if (!$socio) {
            return redirect()->back()->withInput()->with('error', 'Error al seleccionar el Socio.');
          }
          
          //valido que sean menores de edad
          if(!$this->isCadete($socio->fechaNac)){
            return redirect()->back()->withInput()->with('error', 'Los socios (cadetes) deben ser menores de edad.');
          }

          //valido que no pertenezcan a otro grupo
          if ($socio->idGrupoFamiliar) {
            return redirect()->back()->withInput()->with('error', 'Alguno de los miembros seleccionados ya pertenece a un Grupo Familiar.');
          }

          //valido que el cadete esté activo
          if ($socio->activo == 0) {
            return redirect()->back()->withInput()->with('cadeteInactivo', 'Uno o más cadetes que se intentan agregar se encuentran inactivos.');
          }

          //actualizo el socio
          $socio->idGrupoFamiliar = $request->id;
          $socio->save();
        }
      }
      

      //redirijo a la vista individual del grupo
      return redirect()->action('GrupoFamiliarController@getShowId', $request->id);
  }

    /**
   * Destroys a Cadete register passed by ID
   * 
   * @param Request $request
   */
  public function destroyMenor(Request $request)
  {
    //busco el socio
    $socio = Socio::find($request->id);

    if($socio == null) {
      return redirect()->back()->withInput()->with('errorEliminar', 'Error al eliminar el socio.');
    }

    //tomo el grupo familiar
    $grupo = GrupoFamiliar::find($socio->idGrupoFamiliar);

    // valido si el grupo posee mmenos de 2 miembros
    if(sizeof($grupo->socios) < 3)
    {
      return redirect()->back()->withInput()->with('errorEliminar', 'No se puede eliminar el integrante, el Grupo Familiar quedaría con un solo miembro.');
    }

    $socio->idGrupoFamiliar = null;
    $socio->save();

    //retorno la vista
    return redirect()->action('GrupoFamiliarController@getShowId', $grupo->id);
  }
  
  /**
   * Cambia titular por pareja, y pareja por titular
   * 
   * @param Request $request
   */
  public function cambiarRoles(Request $request)
  {
    $grupo = GrupoFamiliar::find($request->id);
    
    $roleSwapper = $grupo->titular;
    $grupo->titular = $grupo->pareja;
    $grupo->pareja = $roleSwapper;

    $grupo->save();

    return redirect()->action('GrupoFamiliarController@getShowId', $request->id);
  }

  
  /**
   * Remove the specified resource from storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    //elimino el grupo con tal id
    $grupo = GrupoFamiliar::destroy($request->id);

    //redirijo al listado
    return redirect()->action('GrupoFamiliarController@getShow');
  }

}
