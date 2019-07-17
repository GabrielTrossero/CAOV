<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GrupoFamiliar;
use App\Socio;

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

      //valido los datos ingresados
      $validacion = Validator::make($request->all(),[
        'titular' => 'required|unique:grupofamiliar'
      ]);

      //si la validacion falla vuelvo hacia atras con los errores
      if($validacion->fails())
      {
        return redirect()->back()->withErrors($validacion->errors());
      }

      //almaceno el grupo familiar en la BD
      $grupo->create($request->all());

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
        return view('grupoFamiliar.individual', ['grupo' , $grupo]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //tomo el grupo a actualizar
        $grupo = GrupoFamiliar::find($request->id);

        //convierto a int los valores de titular, accionMiembro y miembro de $request
        $titular = intval($request->titular);
        $accionMiembro = intval($request->accionMiembro);
        $miembro = intval($request->miembro);

        //si el numero de titular es distinto a cero y distinto al titular actual se actualiza el titular del grupo
        if (($titular != 0) && ($titular != $grupo->titular)){
          $grupo->titular = $titular;
          $grupo->save();
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

            //actualizo el grupo familiar del socio que sale
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
