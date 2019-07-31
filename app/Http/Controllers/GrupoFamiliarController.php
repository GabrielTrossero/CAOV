<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\GrupoFamiliar;
use App\Socio;
use Carbon\Carbon;

class GrupoFamiliarController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //tomo los socios
        $socios = Socio::where('idGrupoFamiliar', null)->get();

        //redirijo a la vista de agregar con los socios
        return view('grupoFamiliar.agregar', compact('socios'));
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
        'pareja.unique' => 'La Pareja del Titular ya pertenece a otro Grupo Familiar.'
      ];

      //valido los datos ingresados
      $validacion = Validator::make($request->all(),[
        'titular' => 'required|unique:grupofamiliar',
        'pareja' => 'unique:grupofamiliar'
      ], $messages);

      //valido si los socios titular y pareja son distintos
      if($request->titular == $request->pareja)
      {
        return redirect()->back()->withInput()->with('errorIguales', 'Los Socios para Titular y Pareja son la misma Persona, por favor revisar la selección.');
      }

      //si la validacion falla vuelvo hacia atras con los errores
      if($validacion->fails())
      {
        return redirect()->back()->withInput()->withErrors($validacion->errors());
      }

      //almaceno el grupo familiar en la BD
                /*$grupo->create($request->all());*/
      $grupo->titular = $request->titular;

      if (isset($request->pareja)) {
        $grupo->pareja = $request->pareja;
      } else {
        $grupo->pareja = NULL;
      }

      $grupo->save();

      //tomo el grupo para pasarlo a la vista individual del mismo
      $grupoRetornado = GrupoFamiliar::where('titular', $request->titular)->first();

      //actualizo el id del grupo familiar del titular
      $socio = Socio::find($request->titular);
      $socio->idGrupoFamiliar = $grupoRetornado->id;
      $socio->save();

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
        //tomo los grupos familiares
        $grupos = GrupoFamiliar::all();

        //redirijo al listado de grupos familiares
        return view('grupoFamiliar.listado', compact('grupos'));
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
    public function edit($id)
    {
        //tomo el grupo familiar
        $grupo = GrupoFamiliar::find($id);

        //tomo los socios sin grupo familiar
        $sociosSinGrupo = Socio::where('idGrupoFamiliar', null)->get();

        //redirijo a la vista de edicion del grupo familiar
        return view('grupoFamiliar.editar', compact('grupo', 'sociosSinGrupo'));
    }

    /**
     * calcula la edad del socio ingresado por parametro
     * @param  App\Socio $socio
     * @return int
     */
    private function calculaEdad($socio)
    {
        //asigna a $edad, la edad del socio calculada a partir de su fecha de nacimiento
        $edad = Carbon::parse($socio->fechaNac)->age;

        //retorna la edad del socio
        return $edad;
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
        $messages = [
          'titular.required' => 'Es necesario ingresar un Socio Titular.',
          ];

        //valido los datos ingresados
        $validacion = Validator::make($request->all(),[
          'titular' => 'required'
        ], $messages);

        //valido si los socios titular y pareja son distintos
        if($request->titular == $request->pareja)
        {
          return redirect()->back()->withInput()->with('errorIguales', 'Los Socios para Titular y Pareja son la misma Persona, por favor revisar la selección.');
        }

        //tomo el grupo a actualizar
        $grupo = GrupoFamiliar::find($request->id);

        //valido si el socio a eliminar es el titular, si lo es redirijo con error
        if(($request->accionMiembro == 2) && ($request->miembro == $grupo->titular))
        {
          return redirect()->back()->withInput()->with('errorEliminacionTitular', 'Se intenta eliminar al Socio Titular, por favor seleccione otro Titular antes de esto.');
        }

        //guardo el id del socio pareja en el grupo
        if ($request->pareja != 0) {
          $grupo->pareja = $request->pareja;
        } else {
          $grupo->pareja = NULL;
        }

        $grupo->save();
        $grupo->refresh();

        //convierto a int los valores de titular, accionMiembro y miembro de $request
        $titular = intval($request->titular);
        $accionMiembro = intval($request->accionMiembro);
        $miembro = intval($request->miembro);

        //si el numero de titular es distinto a cero y distinto al titular actual se actualiza el titular del grupo
        if (($titular != 0) && ($titular != $grupo->titular)){
          $grupo->titular = $titular;
          $grupo->save();
          $grupo->refresh();
        }


        if (($accionMiembro != 0) && ($miembro != 0)) {
          if ($accionMiembro == 1) {
            //tomo el socio que se agrega al grupo
            $socio = Socio::find($miembro);

            //actualizo el grupo familiar del socio agregado
            $socio->idGrupoFamiliar = $grupo->id;
            $socio->save();
          }
          elseif ($accionMiembro == 2) {
            //tomo el socio que sale del grupo
            $socio = Socio::find($miembro);

            if ((isset($grupo->socioPareja)) && ($grupo->socioPareja->id == $socio->id)) {
              $grupo->pareja = NULL;
              $grupo->save();
              $grupo->refresh();
            }

            //actualizo el grupo familiar del socio que sale
            $socio->idGrupoFamiliar = NULL;
            $socio->save();
          }
        }

        //tomo todos los socios del grupo para validar sus edades
        $socios = $grupo->socios;

        foreach ($socios as $socio) {
          $edad = $this->calculaEdad($socio);

          if (($edad >= 18) && ($socio->id != $grupo->titular) && ($socio->id != $grupo->pareja)) {
            $socio->idGrupoFamiliar = NULL;
            $socio->save();
          }
        }

        //refresco el registro de la BD
        $grupo->refresh();

        //redirijo a la vista individual del grupo
        return redirect()->action('GrupoFamiliarController@getShowId', $grupo->id);
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
        return redirect()->action('GrupoFamiliarController@getSHow');
    }
}
