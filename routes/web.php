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
Auth::routes(['register' => false]);

//Grupos de Rutas con el Middleware "auth" y "empleado"
Route::group(['middleware' => ['auth', 'empleado']], function()
{
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



    //Rutas para Cuota
    Route::group(['prefix' => 'cuota'], function()
    {
        Route::get('/', 'CuotaController@index');
        Route::get('createMontoCuota', 'CuotaController@createMontoCuota');
        Route::post('createMontoCuota', 'CuotaController@storeMontoCuota');
        Route::get('showMontoCuota', 'CuotaController@getShowMontoCuota');
        Route::get('showCreateCuota', 'CuotaController@showCreateCuota'); //listado de socios para crear cuota
        Route::get('createCuota/{id}', 'CuotaController@createCuota');
        Route::post('createCuota', 'CuotaController@storeCuota');
        Route::post('storeMontoCuota', 'CuotaController@show');
        Route::get('show', 'CuotaController@getShow'); //listado de cuotas
        Route::get('show/{id}', 'CuotaController@getShowId');
        Route::get('edit/{id}', 'CuotaController@edit');
        Route::post('edit', 'CuotaController@update');
        Route::post('disable', 'CuotaController@disable');
        Route::post('enable', 'CuotaController@enable');
        Route::get('pago/{id}', 'CuotaController@getPago');
        Route::post('pago', 'CuotaController@postPago');
    });
/*
    //Rutas para Pago de Cuota
    Route::group(['prefix' => 'pagocuota'], function()
    {
        Route::get('show', 'PagoCuotaController@getShow');
        Route::get('pago/{id}', 'PagoCuotaController@getPago');
        Route::post('pago', 'PagoCuotaController@postPago');
    });*/


    //Rutas para Pago de Alquiler
    Route::group(['prefix' => 'pagoalquiler'], function()
    {
        Route::get('/', 'PagoAlquilerController@index');
        Route::get('listamueble', 'PagoAlquilerController@getShowMueble');
        Route::get('listainmueble', 'PagoAlquilerController@getShowInmueble');
        Route::get('pagomueble/{id}', 'PagoAlquilerController@getPagoMueble');
        Route::post('pagomueble', 'PagoAlquilerController@postPagoMueble');
        Route::get('pagoinmueble/{id}', 'PagoAlquilerController@getPagoInmueble');
        Route::post('pagoinmueble', 'PagoAlquilerController@postPagoInmueble');
    });

    //Rutas para Registros
    Route::group(['prefix' => 'registro'], function()
    {
        Route::get('/', 'RegistroController@index');
        Route::post('/', 'RegistroController@postRegistro');
    });

    //Grupo de Rutas con el Middleware "admin"
    Route::group(['middleware' => 'admin'], function () {
        //Rutas para Informes
        Route::group(['prefix' => 'informe'], function()
        {
            Route::get('/', 'InformeController@index');

            Route::get('deudores', 'InformeController@getDeudores');
            Route::get('pdf_deudores', 'InformeController@pdfDeudores');

            Route::get('socio_deudor/{id}', 'InformeController@getSocioDeudor');
            Route::get('pdf_socio_deudor', 'InformeController@pdfSocioDeudor');

            Route::get('cantidad_socios', 'InformeController@getCantidadSocios');
            Route::get('pdf_cantidad_socios', 'InformeController@pdfCantidadSocios');

            Route::get('cantidad_socios_deporte', 'InformeController@getCantidadSociosDeporte');
            Route::get('pdf_cantidad_socios_deporte', 'InformeController@pdfCantidadSociosDeporte');

            Route::get('ingresos_egresos', 'InformeController@getIngresosEgresos');
            Route::get('pdf_ingresos_egresos', 'InformeController@pdfIngresosEgresos');

            Route::get('pagos', 'InformeController@getPagos');
            Route::get('pdf_pagos', 'InformeController@pdfPagos');
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
        Route::group(['prefix' => 'deporte'], function()
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
    });



    //Rutas de Alquiler de Muebles
    Route::group(['prefix' => 'alquilermueble'], function()
    {
        Route::get('/', 'AlquilerMuebleController@index');
        Route::get('create', 'AlquilerMuebleController@create');
        Route::post('create', 'AlquilerMuebleController@store');
        Route::get('show', 'AlquilerMuebleController@getShow');
        Route::get('show/{id}', 'AlquilerMuebleController@getShowId');
        Route::get('edit/{id}', 'AlquilerMuebleController@edit');
        Route::post('edit', 'AlquilerMuebleController@update');
        Route::post('delete', 'AlquilerMuebleController@destroy');
    });

    //Rutas de Alquiler de Inmuebles
    Route::group(['prefix' => 'alquilerinmueble'], function()
    {
        Route::get('/', 'AlquilerInmuebleController@index');
        Route::get('create', 'AlquilerInmuebleController@create');
        Route::post('create', 'AlquilerInmuebleController@store');
        Route::get('show', 'AlquilerInmuebleController@getShow');
        Route::get('show/{id}', 'AlquilerInmuebleController@getShowId');
        Route::get('edit/{id}', 'AlquilerInmuebleController@edit');
        Route::post('edit', 'AlquilerInmuebleController@update');
        Route::post('delete', 'AlquilerInmuebleController@destroy');
    });
});
