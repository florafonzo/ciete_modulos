<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'InicioController@index');
Route::get('/descripcion/actividad/{id}', 'InicioController@descCurso');
Route::get('/descripcion/webinar/{id}', 'InicioController@descWebinar');



//Rutas Información CIETE y Créditos
Route::get('mision-y-vision','InformacionController@mision_vision');
Route::get('estructura','InformacionController@estructura');
Route::get('servicios','InformacionController@servicios');
Route::get('equipo','InformacionController@equipo');
Route::get('creditos','InformacionController@creditos');
Route::get('ayuda','InformacionController@ayuda');
Route::get('ayuda/manual', 'InformacionController@descargarAyuda');

//Rutas de correo
Route::post('/password/email', 'Auth\PasswordController@postEmail');
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('/password/reset', 'Auth\PasswordController@postReset');

//Ruta de contacto
Route::get('contacto','InformacionController@getcontacto');
Route::post('contacto','InformacionController@postContacto');

//Ruta prenscripcion
Route::get('inscripcion/procedimiento','PreinscripcionController@showProcedimiento');

Route::get('inscripcion/actividades', 'PreinscripcionController@mostrarPreinscripcionCurso');
Route::get('inscripcion/webinars', 'PreinscripcionController@mostrarPreinscripcionWebinar');
Route::post('inscripcion/actividades','PreinscripcionController@storePreinscripcionCurso');
Route::post('inscripcion/webinars','PreinscripcionController@storePreinscripcionWebinar');

//Rutas Loggin y recuperación de contraseñas
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);


Route::group([
    'middleware' => 'auth'],
    function(){
    //Rutas manejo de usuarios//
    Route::get('usuarios/buscar', [
        'as' => 'usuarios.buscar', 'uses' => 'UsuariosController@buscar'
    ]);
        Route::get('usuarios/{id}/documento_identidad','UsuariosController@cambiarArchivoDi');
        Route::get('usuarios/{id}/titulo','UsuariosController@cambiarArchivoT');
        Route::get('usuarios/archivo/{doc}/ver', 'UsuariosController@verPdf');
    Route::resource('usuarios','UsuariosController');

    //Rutas manejo de actividades

//        Route::get('actividades', 'CursosController@index');
//        Route::get('actividades/create', 'CursosController@create');
//        Route::post('actividades', 'CursosController@store');
//        Route::get('actividades/{id}/edit',[
//            'as' => 'actividades.edit', 'uses' => 'CursosController@edit'
//        ]);
//        Route::patch('actividades/{id}', [
//            'as' => 'actividades.update', 'uses' => 'CursosController@update'
//        ]);
//        Route::delete('actividades/{id}', [
//            'as' => 'actividades.destroy', 'uses' => 'CursosController@delete'
//        ]);

    Route::get('actividades/buscar', [
        'as' => 'cursos.buscar', 'uses' => 'CursosController@buscar'
    ]);
    Route::get('actividades/desactivados', 'CursosController@indexDesactivados');
    Route::get('actividades/desactivados/activar/{id}', 'CursosController@activar');
    Route::get('actividades/desactivados/buscar', [
        'as' => 'actividades/desactivados.buscar', 'uses' => 'CursosController@buscar2'
    ]);

        Route::get('actividades/imagen','CursosController@cambiarImagen');
        Route::post('actividades/procesar','CursosController@procesarImagen');

    Route::get('actividades/imagen/{id}','CursosController@cambiarImagen1');
    Route::post('actividades/procesar/{id}','CursosController@procesarImagen1');

    Route::get('actividades/{id}/grupos', 'CursosController@seccionesMoodle');
    Route::get('actividades/{id}/grupos/{seccion}/lista', 'CursosController@listaMoodle');

    Route::get('actividades/{id}/grupos/participantes', 'CursosController@cursoSeccionesParts');
    Route::get('actividades/{id_curso}/grupos/{seccion}/participantes', 'CursosController@cursoParticipantes');
        Route::get('actividades/{id_curso}/grupos/{seccion}/participantes/buscar', [
            'as' => 'cursos.participantes.buscar', 'uses' => 'CursosController@buscarParticipante'
        ]);
    Route::get('actividades/{id_curso}/grupos/{seccion}/participantes/agregar', 'CursosController@cursoParticipantesAgregar');
        Route::get('actividades/{id_curso}/grupos/{seccion}/participantes/agregar/buscar', [
            'as' => 'cursos.participantes.buscar', 'uses' => 'CursosController@buscarParticipanteAgregar'
        ]);
    Route::get('actividades/{id_curso}/grupos/{seccion}/participantes/{id_part}/agregar', 'CursosController@cursoParticipantesGuardar');
    Route::delete('actividades/{id_curso}/grupos/{seccion}/participantes/{id_part}/eliminar', 'CursosController@cursoParticipantesEliminar');
    Route::get('actividades/{id}/modulos/profesores', 'CursosController@cursoModulosProfes');
    Route::get('actividades/{id}/modulos/{modulo}/profesores', 'CursosController@cursoProfesores');
        Route::get('actividades/{id_curso}/modulos/{modulo}/profesores/buscar', [
            'as' => 'cursos.profesores.buscar', 'uses' => 'CursosController@buscarProfesor'
        ]);
    Route::get('actividades/{id}/modulos/{modulo}/profesores/agregar', 'CursosController@cursoProfesoresAgregar');
        Route::get('actividades/{id_curso}/modulos/{modulo}/profesores/agregar/buscar', [
            'as' => 'cursos.participantes.buscar', 'uses' => 'CursosController@buscarProfesorAgregar'
        ]);
    Route::get('actividades/{id_curso}/modulos/{modulo}/profesores/{id_prof}/agregar', 'CursosController@cursoProfesoresGuardar');
    Route::delete('actividades/{id_curso}/modulos/{modulo}/profesores/{id_prof}/eliminar', 'CursosController@cursoProfesoresEliminar');

    Route::resource('actividades','CursosController',['only' => ['index','create','store','edit','update', 'destroy']]);

    //Rutas manejo de roles
    Route::get('roles/buscar', [
        'as' => 'roles.buscar', 'uses' => 'RolesController@buscar'
    ]);
    Route::resource('/roles','RolesController');

    //Rutas manejo de webinars
    Route::get('webinars/buscar', [
        'as' => 'webinars.buscar', 'uses' => 'WebinarsController@buscar'
    ]);
    Route::get('webinars/desactivados', 'WebinarsController@indexDesactivados');//cambiar
    Route::get('webinars/desactivados/activar/{id}', 'WebinarsController@activar');
    Route::get('webinars/desactivados/buscar', [
        'as' => 'webinars/desactivados.buscar', 'uses' => 'WebinarsController@buscar2'
    ]);

        Route::get('webinars/imagen','WebinarsController@cambiarImagen');
        Route::post('webinars/procesar','WebinarsController@procesarImagen');

        Route::get('webinars/imagen/{id}','WebinarsController@cambiarImagen1');
        Route::post('webinars/procesar/{id}','WebinarsController@procesarImagen1');

//    Route::get('webinars/{id}/grupos', 'WebinarsController@seccionesMoodle');
//    Route::get('webinars/{id}/grupos/{seccion}/lista', 'WebinarsController@listaMoodle');

//    Route::get('webinars/{id}/grupos/participantes', 'WebinarsController@WebinarSeccionesParts');
    Route::get('webinars/{id}/participantes', 'WebinarsController@webinarParticipantes');
        Route::get('webinars/{id_curso}/participantes/buscar', [
            'as' => 'webinars.participantes.buscar', 'uses' => 'WebinarsController@buscarParticipante'
        ]);
    Route::get('webinars/{id}/participantes/agregar', 'WebinarsController@webinarParticipantesAgregar');
        Route::get('webinars/{id_webinar}/participantes/agregar/buscar', [
            'as' => 'webinars.participantes.buscar', 'uses' => 'WebinarsController@buscarParticipanteAgregar'
        ]);
    Route::get('webinars/{id_webinar}/participantes/{id_part}/agregar', 'WebinarsController@webinarParticipantesGuardar');
    Route::delete('webinars/{id_webinar}/participantes/{id_part}/eliminar', 'WebinarsController@webinarParticipantesEliminar');

//    Route::get('webinars/{id}/grupos/profesores', 'WebinarsController@WebinarSeccionesProfes');
    Route::get('webinars/{id}/profesores', 'WebinarsController@webinarProfesores');
        Route::get('webinars/{id_curso}/profesores/buscar', [
            'as' => 'webinars.profesores.buscar', 'uses' => 'WebinarsController@buscarProfesor'
        ]);
    Route::get('webinars/{id}/profesores/agregar', 'WebinarsController@webinarProfesoresAgregar');
        Route::get('webinars/{id_webinar}/profesores/agregar/buscar', [
            'as' => 'webinars.profesores.buscar', 'uses' => 'WebinarsController@buscarProfesorAgregar'
        ]);
    Route::get('webinars/{id_webinar}/profesores/{id_part}/agregar', 'WebinarsController@webinarProfesoresGuardar');
    Route::delete('webinars/{id_webinar}/profesores/{id_part}/eliminar', 'WebinarsController@webinarProfesoresEliminar');
    Route::resource('webinars','WebinarsController', ['only' => ['index','create','store','update','edit', 'destroy']]);

    //Rutas dirección participantes
    Route::get('/ciudad/{id}', function(){
        $url = Request::url();
        $porciones = explode("ciudad/", $url);
        $id = $porciones[1];
        $ciudades = App\Models\Ciudad::where('id_estado', '=', $id )->get();

        return Response::json($ciudades);
    });

    Route::get('/municipio/{id}', function(){
        $url = Request::url();
        $porciones = explode("municipio/", $url);
        $id = $porciones[1];
        $municipios = App\Models\Municipio::where('id_estado', '=', $id )->get();

        return Response::json($municipios);
    });

    Route::get('/parroquia/{id}', function(){
        $url = Request::url();
        $porciones = explode("parroquia/", $url);
        $municipio = $porciones[1];

        $parroquias = App\Models\Parroquia::where('id_municipio', '=', $municipio )->get();

        return Response::json($parroquias);
    });

    //Rutas participante
    Route::get('participante/perfil','ParticipantesController@verPerfil');
    Route::get('participante/perfil/{id}/editar','ParticipantesController@editarPerfil');
    Route::get('participante/perfil/imagen','ParticipantesController@cambiarImagen');
    Route::post('participante/perfil/procesar','ParticipantesController@procesarImagen');
    Route::get('participante/perfil/documento_identidad','ParticipantesController@cambiarArchivoDi');
    Route::get('participante/perfil/titulo','ParticipantesController@cambiarArchivoT');
    Route::get('participante/perfil/archivo/{doc}/ver', 'ParticipantesController@verPdf');
//    Route::patch('participante/perfil/{id}','ParticipantesController@update');
    Route::get('participante/actividades','ParticipantesController@verCursos');
    Route::get('participante/actividades/{id}/modulos','ParticipantesController@verModulosCurso');
    Route::get('participante/actividades/{id}/modulos/{modulo}/notas','ParticipantesController@verNotasCurso');

        Route::get('participante/actividades/{id}/pagos', 'ParticipantesController@verPagosCurso');
        Route::get('participante/actividades/{id}/pagos/{id_pago}',[
            'as' => 'participante.recibo',
            'uses' => 'ParticipantesController@reciboPagoCurso'
        ]);
        Route::get('participante/actividades/{id}/nuevo/pago',[
            'as' => 'participante.nuevo',
            'uses' => 'ParticipantesController@generarPagoCurso'
        ]);
//        Route::post('participante/actividades/{id}/pagos/generar','ParticipantesController@guardarPagoCurso');
        Route::post('participante/actividades/{id}/pagos/generar',[
            'as' => 'participante.pago',
            'uses' => 'ParticipantesController@guardarPagoCurso'
        ]);

    Route::get('participante/webinars','ParticipantesController@verWebinars');
    Route::resource('participante','ParticipantesController');


    //Rutas preinscripción
    Route::get('inscripcion/actividades/procesar','PreinscripcionController@index');
    Route::get('inscripcion/webinars/procesar','PreinscripcionController@indexWeb');
//    Route::post('inscripcion/principal', 'PreinscripcionController@mostrar');
    Route::get('inscripcion/actividades/activar/{id}',[
        'as' => 'preinscripcion.activar',
        'uses' => 'PreinscripcionController@activarPreinscripcion'
    ]);
    Route::get('inscripcion/actividades/desactivar/{id}',[
        'as' => 'preinscripcion.desactivar',
        'uses' => 'PreinscripcionController@desactivarPreinscripcion'
    ]);
    Route::get('inscripcion/webinars/activar/{id}',[
        'as' => 'preinscripcion.activar-web',
        'uses' => 'PreinscripcionController@activarPreinscripcionWeb'
    ]);
    Route::get('inscripcion/webinars/desactivar/{id}',[
        'as' => 'preinscripcion.desactivar-web',
        'uses' => 'PreinscripcionController@desactivarPreinscripcionWeb'
    ]);

    //Rutas inscripcion
        Route::get('inscripcion/procesar','InscripcionController@index');
        Route::post('inscripcion/procesar',[
            'as' => 'inscripcion.store', 'uses' =>'InscripcionController@store']);
        Route::delete('inscripcion/procesar/{id}',
            ['as' => 'inscripcion.destroy', 'uses' =>'InscripcionController@destroy']);
        Route::get('inscripcion/procesar/buscar', [
            'as' => 'inscripcion.buscar', 'uses' => 'InscripcionController@buscarInscripcion'
        ]);
        Route::get('inscripcion/procesar/{id}/documentos', [
            'as' => 'inscripcion.documentos', 'uses' => 'InscripcionController@verDocumentos'
        ]);
        Route::get('inscripcion/procesar/{doc}/documentos/ver', [
            'as' => 'inscripcion.verPdf', 'uses' => 'InscripcionController@verPdf'
        ]);
        Route::get('inscripcion/completar/{id}','InscripcionController@completarInscripcion');
//        Route::resource('inscripcion','InscripcionController');


    //Rutas informes académicos
    Route::get('informes/{id}/profesor/{id_prof}/actividad/{id_actividad}/modulo/{id_modulo}/grupos/{seccion}','InformesController@verInforme');
        Route::get('informes/buscar', [
            'as' => 'informes.buscar', 'uses' => 'InformesController@buscarInforme'
        ]);
    Route::resource('informes','InformesController');

    // Pagos
        Route::post('pagos/aprobar', [
            'as' => 'pago.aprobar', 'uses' => 'PagosController@aprobarPago'
        ]);
        Route::delete('pagos/rechazar/{id}', [
            'as' => 'pago.destroy', 'uses' => 'PagosController@destroy'
        ]);
        Route::get('pagos/buscar', [
            'as' => 'pago.buscar',
            'uses' => 'PagosController@buscarPago'
        ]);
    Route::resource('pagos', 'PagosController');



        //Rutas profesores
    Route::get('profesor/perfil','ProfesoresController@verPerfil');
    Route::get('profesor/perfil/{id}/editar','ProfesoresController@editarPerfil');
    Route::get('profesor/perfil/imagen','ProfesoresController@cambiarImagen');
    Route::post('profesor/perfil/procesar','ProfesoresController@procesarImagen');
    Route::get('profesor/actividades','ProfesoresController@verCursos');
    Route::get('profesor/actividades/buscar', [
        'as' => 'profesor.cursos.buscar', 'uses' => 'ProfesoresController@buscarCurso'
    ]);
    Route::get('profesor/actividades/{id}/modulos','ProfesoresController@verModulosCurso');
    Route::get('profesor/actividades/{id}/modulos/{modulo}/grupos/{seccion}/informe/datos','ProfesoresController@datosInforme');
    Route::post('profesor/actividades/{id}/modulos/{modulo}/grupos/{seccion}/informe/datos/generar',[
        'as' => 'profesor.informe','uses' => 'ProfesoresController@generarInformeAc']);
    Route::get('profesor/actividades/{id}/modulos/{modulo}/grupos','ProfesoresController@verSeccionesCurso');
    Route::get('profesor/actividades/{id}/modulos/{modulo}/grupos/{seccion}/participantes','ProfesoresController@verParticipantesSeccion');
        Route::get('profesor/actividades/{id}/modulos/{modulo}/grupos/{seccion}/participantes/buscar', [
            'as' => 'profesor.cursos.participantes.buscar', 'uses' => 'ProfesoresController@buscarParticipante'
        ]);
    Route::get('profesor/actividades/{id}/modulos/{modulo}/grupos/{seccion}/lista','ProfesoresController@generarLista');
    Route::get('profesor/actividades/{id}/modulos/{modulo}/grupos/{seccion}/participantes/{id_alumno}/notas','ProfesoresController@verNotasParticipante');
    Route::post('profesor/actividades/{id}/modulos/{modulo}/grupos/{seccion}/participantes/{id_alumno}/notas','ProfesoresController@store');
    Route::delete('profesor/actividades/{id}/modulos/{modulo}/grupos/{seccion}/participantes/{id_alumno}/notas/{id_nota}','ProfesoresController@eliminarNotasParticipante');
//    Route::get('profesor/webinars','ProfesoresController@verWebinars');
//    Route::get('profesor/webinars/{id}/grupos','ProfesoresController@verSeccionesWebinar');
    Route::get('/nota/{id}', function(){
        $url = Request::url();
        $porciones = explode("nota/", $url);
        $nota = $porciones[1];

        $nota = App\Models\Nota::where('id', '=', $nota )->get();

        return Response::json($nota);
    });

    Route::get('profesor/webinars','ProfesoresController@verWebinars');
        Route::get('profesor/webinars/buscar', [
            'as' => 'profesor.webinars.buscar', 'uses' => 'ProfesoresController@buscarWebinar'
        ]);
    Route::get('profesor/webinars/{id}/grupos','ProfesoresController@verSeccionesWebinar');
    Route::get('profesor/webinars/{id}/grupos/{seccion}/participantes','ProfesoresController@verParticipantesWebinar');
        Route::get('profesor/webinars/{id}/grupos/{seccion}/participantes/buscar', [
            'as' => 'profesor.webinars.participantes.buscar', 'uses' => 'ProfesoresController@buscarParticipanteW'
        ]);
    Route::get('profesor/webinars/{id}/grupos/{seccion}/lista','ProfesoresController@generarListaW');

    Route::resource('/profesor','ProfesoresController');
});

//Route::get('/', 'WelcomeController@index');

//Route::get('home', 'HomeController@index');