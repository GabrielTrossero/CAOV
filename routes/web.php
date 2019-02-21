<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

//Grupos de Rutas con el Middleware "auth"
/*Route::group(['middleware' => 'auth'], function()
{*/
    //Ruta del Home
    Route::get('/', 'HomeController@index');

    //Rutas de Personas
    Route::group(['prefix' => 'persona'], function()
    {
        Route::get('/', 'PersonaController@index');
        Route::get('create', 'PersonaController@create');
        Route::post('create', 'PersonaController@store');
        Route::get('show', 'PersonaController@getShow');
        Route::get('show/{id}', 'PersonaController@getShowId');
        Route::get('edit/{id}', 'PersonaController@edit');
        Route::post('edit', 'PersonaController@update');
        Route::post('delete', 'PersonaController@destroy');
    });

    //Rutas de Socios
    Route::group(['prefix' => 'socio'], function()
    {
        Route::get('/', 'SocioController@index');
        Route::get('create', 'SocioController@create');
        Route::post('create', 'SocioController@store');
        Route::get('show', 'SocioController@getShow');
        Route::get('show/{id}', 'SocioController@getShowId');
        Route::get('edit/{id}', 'SocioController@edit');
        Route::post('edit', 'SocioController@update');
        Route::post('delete', 'SocioController@destroy');
    });

    //Rutas de Grupos Familiares
    Route::group(['prefix' => 'grupofamiliar'], function()
    {
        Route::get('/', 'GrupoFamiliarController@index');
        Route::get('create', 'GrupoFamiliarController@create');
        Route::post('create', 'GrupoFamiliarController@store');
        Route::get('show', 'GrupoFamiliarController@getShow');
        Route::get('show/{id}', 'GrupoFamiliarController@getShowId');
        Route::get('edit/{id}', 'GrupoFamiliarController@edit');
        Route::post('edit', 'GrupoFamiliarController@update');
        Route::post('delete', 'GrupoFamiliarController@destroy');
    });

    //Rutas de Inmuebles
    Route::group(['prefix' => 'inmueble'], function()
    {
        Route::get('/', 'InmuebleController@index');
        Route::get('create', 'InmuebleController@create');
        Route::post('create', 'InmuebleController@store');
        Route::get('show', 'InmuebleController@getShow');
        Route::get('show/{id}', 'InmuebleController@getShowId');
        Route::get('edit/{id}', 'InmuebleController@edit');
        Route::post('edit', 'InmuebleController@update');
        Route::post('delete', 'InmuebleController@destroy');
    });

    //Rutas de Muebles
    Route::group(['prefix' => 'mueble'], function()
    {
        Route::get('/', 'MuebleController@index');
        Route::get('create', 'MuebleController@create');
        Route::post('create', 'MuebleController@store');
        Route::get('show', 'MuebleController@getShow');
        Route::get('show/{id}', 'MuebleController@getShowId');
        Route::get('edit/{id}', 'MuebleController@edit');
        Route::post('edit', 'MuebleController@update');
        Route::post('delete', 'MuebleController@destroy');
    });

    //Rutas para Cuota
    Route::group(['prefix' => 'cuota'], function()
    {
        Route::get('/', 'CuotaController@index');
        Route::get('pago/{id}', 'CuotaController@getPago');
        Route::post('pago', 'CuotaController@postPago');
    });

    //Rutas para Pago de Alquiler
    Route::group(['prefix' => 'pagoalquiler'], function()
    {
        Route::get('/', 'PagoAlquilerController@index');
        Route::get('pago/{id}', 'PagoAlquilerController@getPago');
        Route::post('pago', 'PagoAlquilerController@postPago');
    });

    //Rutas para Registros
    Route::group(['prefix' => 'registros'], function()
    {
        Route::get('/', 'RegistroController@index');
        Route::post('/', 'RegistroController@postRegistro');
    });

    //Rutas para Informes
    Route::group(['prefix' => 'informes'], function()
    {
        Route::get('/', 'InformeController@index');
        Route::get('deudores', 'InformeController@getDeudores');
        Route::post('deudores', 'InformeController@postDeudores');
        Route::get('cantidad_socios', 'InformeController@getCantidadSocios');
        Route::post('cantidad_socios', 'InformeController@postCantidadSocios');
        Route::get('cantidad_socios_deporte', 'InformeController@getCantidadSociosDeporte');
        Route::post('cantidad_socios_deporte', 'InformeController@postCantidadSociosDeporte');
        Route::get('ingresos_egresos', 'InformeController@getIngresosEgresos');
        Route::post('ingresos_egresos', 'InformeController@postIngresosEgresos');
        Route::get('pagos', 'InformeController@getPagos');
        Route::post('pagos', 'InformeController@postPagos');
    });

    //Rutas para Empleados
    Route::group(['prefix' => 'empleado'], function()
    {
        Route::get('/', 'EmpleadoController@index');
        Route::get('create', 'EmpleadoController@create');
        Route::post('create', 'EmpleadoController@store');
        Route::get('show', 'EmpleadoController@getShow');
        Route::get('show/{id}', 'EmpleadoController@getShowId');
        Route::get('edit/{id}', 'EmpleadoController@edit');
        Route::post('edit', 'EmpleadoController@update');
        Route::post('delete', 'EmpleadoController@destroy');
    });

    //Rutas para Administradores
    Route::group(['prefix' => 'administrador'], function()
    {
        Route::get('/', 'AdministradorController@index');
        Route::post('backup', 'AdministradorController@postBackup');
        Route::get('ingresos', 'AdministradorController@getIngresos');
    });


    //Rutas de Deportes
    Route::group(['prefix' => 'deportes'], function()
    {
        Route::get('/', 'DeporteController@index');
        Route::get('create', 'DeporteController@create');
        Route::post('create', 'DeporteController@store');
        Route::get('show', 'DeporteController@getShow');
        Route::get('show/{id}', 'DeporteController@getShowId');
        Route::get('edit/{id}', 'DeporteController@edit');
        Route::post('edit', 'DeporteController@update');
        Route::post('delete', 'DeporteController@destroy');
    });
//});