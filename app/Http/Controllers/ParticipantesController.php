<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Banco;
use App\Models\Curso;
use App\Models\ModalidadPago;
use App\Models\Modulo;
use App\Models\Nota;
use App\Models\Pago;
use App\Models\ParticipanteCurso;
use App\Models\ParticipanteWebinar;
use App\Models\TipoCurso;
use App\Models\Pais;
use App\Models\Estado;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;
use Response;
use DB;
use Mail;
use DateTime;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade as PDF;

use App\Models\Participante;
use App\Models\Webinar;
use Illuminate\Http\Request;
use App\Http\Requests\ParticipanteRequest;
use App\Http\Requests\PagoRequest;
use App\user;


class ParticipantesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        try {
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_perfil_part')) { // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                return view('inicio', $data);

            }else{      // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}

	/**
	 * Muestra los datos de perfil de participante
	 *
	 * @return Vista con los datos
	 */
	public function verPerfil()
	{
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_perfil_part')) {     // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['datos'] = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del participante
                $data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del participante;
//                dd($data['datos']);

                return view('participantes.ver-perfil', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}


    /**
     * Muestra el formulario para editar los datos de perfil del usuario
     *
     * @return Response
     */
    public function editarPerfil($id)
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
                Session::flash('imagen', 'yes');
            }

            if($usuario_actual->can('editar_perfil_part')) { // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['datos'] = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del participante
                if($data['datos'][0]->di_file == ''){
                    Session::flash('di_f', 'yes');
                }else{
                    Session::flash('di_f', null);
                }
                if($data['datos'][0]->titulo_pregrado == ''){
                    Session::flash('titulo_', 'yes');
                }else{
                    Session::flash('titulo_', null);
                }
                $data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del participante;
                $data['paises'] = Pais::all()->lists('nombre_mostrar', 'id');
                $data['estados'] = Estado::all()->lists('estado','id_estado');

                return view('participantes.editar-perfil', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

	/**
	 * Actualiza los datos de perfil del participante.
     *
     * @param   int     $id                     //id del participante
     * @param   ParticipanteRequest $request    // Se validan los campos ingresados por el usuario antes guardarlos mediante el Request
	 *
	 * @return Response
	 */
	public function update(ParticipanteRequest $request,$id)
	{
        try {

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_perfil_part')) {  // Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $usuario = User::find($id); // Se obtienen los datos del participante que se desea editar
                $participante = Participante::where('id_usuario', '=', $id)->get(); // Se obtiene el resto de los datos del participante que se desea editar
                $img_nueva = Input::get('cortar');//Session::get('cortar');
                $di_f = Input::get('di_f');
                $titulo_ = Input::get('titulo_');
                $data['paises'] = Pais::all()->lists('nombre_mostrar', 'id');
                $data['estados'] = Estado::all()->lists('estado', 'id_estado');

                $email = $request->email;
                // Se verifica si el correo ingresado es igual al anterior y si no lo es se verifica
                // que no conicida con los de las base de datos ya que debe ser único
                if ($email != $usuario->email) {

                    $existe = DB::table('users')->where('email', '=', $email)->first();

                    // Si el correo conicide con alguno de la base de datos se redirige al participante al
                    // formulario de edición indicandole el error
                    if ($existe) {
                        Session::set('error', 'El correo ya existe, ingrese uno diferente.');
//                        $data['errores'] = "El correo ya existe, ingrese uno diferente";
                        $data['datos'] = Participante::where('id_usuario', '=', $id)->get(); // Se obtienen los datos del participante
                        $data['email']= User::where('id', '=', $id)->get(); // Se obtiene el correo principal del participante;

                        return view('participantes.editar-perfil', $data);
                    }
                }
//                dd($participante[0]->nuevo);
                if($participante[0]->nuevo == true) {
                    $data['datos'] = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del participante
                    $data['email'] = User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del participante;
                    $pais = Input::get('id_pais');
                    if ($pais == 231) {
                        $estado = Input::get('id_est');
                        $ciudad = Input::get('ciudad');
                        $municipio = Input::get('municipio');
                        $parroquia = Input::get('parroquia');
                        if (($estado == 0) || ($ciudad == 0) || ($municipio == 0) || ($parroquia == 0)) {
                            Session::set('error', 'Debe completar todos los datos de la direecion (Estado, Ciudad, Municipio y Parroquia).');
//                            $data['errores'] = "Debe completar todos los datos de la direecion (Estado, Ciudad, Municipio y Parroquia)";
                            $data['paises'] = Pais::all()->lists('nombre_mostrar', 'id');
                            $data['estados'] = Estado::all()->lists('estado', 'id_estado');
                            $data['datos'] = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del participante
                            $data['email'] = User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del participante;

                            return view('participantes.editar-perfil', $data);
                        }
                        $dir = $pais . '-' . $estado . '-' . $ciudad . '-' . $municipio . '-' . $parroquia;

                    } elseif ($pais == 0) {
                        Session::set('error', 'Debe completar el campo pais.');
//                        $data['errores'] = "Debe completar el campo pais";
                        return view('participantes.editar-perfil', $data);
                    } else {
                        $dir = $pais;
                    }
                    $participante[0]->direccion = $dir;
                    $participante[0]->nuevo = false;
                }

                //  Se valida que los archivos esten en formato PDF
                if($request->hasFile('archivo_documento_identidad') && $di_f == 'yes') {
                    $validar_di = Validator::make(array('archivo_documento_identidad' => $request->file('archivo_documento_identidad')), array('archivo_documento_identidad' => 'mimes:pdf'));
                    if ($validar_di->fails()) {
                        $data['datos'] = Participante::where('id_usuario', '=', $id)->get(); // Se obtienen los datos del participante
                        $data['email']= User::where('id', '=', $id)->get(); // Se obtiene el correo principal del participante;
                        Session::set('error', 'El archivo documento identidad debe estar en formato PDF');
                        return view('participantes.editar-perfil', $data);
                    }
                    // Se crea el nombre del archivo y se guarda
                    $nombreDI = 'D_identidad_' . date('dmY') . '_' . date('His') . '.pdf';
                    if($participante[0]->di_file != ''){ // se borra el qrchivo anterior si existe
                        Storage::delete('/documentos/participantes/'.$participante[0]->di_file);
                    }
                    $participante[0]->di_file = $nombreDI;
                    // se guardan los archivos PDF en la carpeta correcta
                    $pdfDI = $request->file('archivo_documento_identidad');
                    Storage::put('/documentos/participantes/'.$nombreDI, \File::get($pdfDI ));
                }
                if($request->hasFile('titulo') && $titulo_ == 'yes') {
                    $validar_di = Validator::make(array('archivo_documento_identidad' => $request->file('archivo_documento_identidad')), array('archivo_documento_identidad' => 'mimes:pdf'));
                    if ($validar_di->fails()) {
                        $data['datos'] = Participante::where('id_usuario', '=', $id)->get(); // Se obtienen los datos del participante
                        $data['email']= User::where('id', '=', $id)->get(); // Se obtiene el correo principal del participante;
                        Session::set('error', 'El archivo título de pregrado debe estar en formato PDF');
                        return view('participantes.editar-perfil', $data);

                    }
                    // Se crea el nombre del archivo y se guarda
                    $nombreTitulo = 'Titulo_' . date('dmY') . '_' . date('His') . '.pdf';
                    if($participante[0]->titulo_pregrado != ''){ // se borra el qrchivo anterior si existe
                        Storage::delete('/documentos/participantes/'.$participante[0]->di_file);
                    }
                    $participante[0]->titulo_pregrado = $nombreTitulo;
                    // se guarda el archivo PDF en la carpeta correcta
                    $pdfTitulo = $request->file('titulo');
                    Storage::put('/documentos/participantes/'.$nombreTitulo, \File::get($pdfTitulo) );
                }

//                Imagen ---
//                if ($img_nueva == 'yes') {
//                    $file = Input::get('dir');
//                    if ($usuario->foto != null) {
//                        Storage::delete('/images/images_perfil/' . $request->file_viejo);
//                    }
//                    $file = str_replace('data:image/png;base64,', '', $file);
//                    $nombreTemporal = 'perfil_' . date('dmY') . '_' . date('His') . ".jpg";
//                    $usuario->foto = $nombreTemporal;
//
//                    $imagen = Image::make($file);
//                    $payload = (string)$imagen->encode();
//                    Storage::put(
//                        '/images/images_perfil/' . $nombreTemporal,
//                        $payload
//                    );
//                }

                $usuario->foto = 'foto_participante.png';


                // Se editan los datos del participante con los datos ingresados en el formulario
                $usuario->nombre = $request->nombre;
                $usuario->apellido = $request->apellido;
                $usuario->email = $email;
                $usuario_actual->password = bcrypt($request->password);
                $usuario->save();   // Se guardan los nuevos datos en la tabla Users

                // Se editan los datos del participante deseado con los datos ingresados en el formulario
                $participante[0]->nombre = $request->nombre;
                $participante[0]->apellido = $request->apellido;
                $participante[0]->documento_identidad = $request->documento_identidad;
                $participante[0]->telefono = $request->telefono;
                $participante[0]->celular = $request->celular;
                $participante[0]->correo_alternativo = $request->correo_alternativo;
                $participante[0]->twitter = Input::get('twitter');
                $participante[0]->ocupacion = Input::get('ocupacion');
//                $participante[0]->titulo_pregrado = Input::get('titulo');
                $participante[0]->universidad = Input::get('univ');
                $participante[0]->save(); // Se guardan los nuevos datos en la tabla Participentes


                //  Si se actualizaron con exito los datos del usuario, se actualizan los roles del usuario.
                if ($usuario->save()) {
                    if ($participante[0]->save()) {
                        Session::set('mensaje','Datos guardados satisfactoriamente.');
                        $data['datos'] = Participante::where('id_usuario', '=', $id)->get(); // Se obtienen los datos del participante
                        $data['email']= User::where('id', '=', $id)->get(); // Se obtiene el correo principal del participante;
                        $data['foto'] = $data['email'][0]->foto;
                        return view('participantes.ver-perfil', $data);

                    } else {    // Si el usuario no se ha actualizo con exito en la tabla Participante
                    // se redirige al formulario de creación y se le indica al usuario el error
                        Session::set('error', 'Ha ocurrido un error inesperado');
                        $data['datos'] = Participante::where('id_usuario', '=', $id)->get(); // Se obtienen los datos del participante
                        $data['email']= User::where('id', '=', $id)->get(); // Se obtiene el correo principal del participante;

                        return view('participantes.editar-perfil', $data);
                    }
                    // Si el usuario no se ha actualizo con exito en la tabla Users
                    // se redirige al formulario de edicion y se le indica al usuario el error
                } else {
                    Session::set('error', 'Ha ocurrido un error inesperado');
                    $data['datos'] = Participante::where('id_usuario', '=', $id)->get(); // Se obtienen los datos del participante
                    $data['email']= User::where('id', '=', $id)->get(); // Se obtiene el correo principal del participante;

                    return view('participantes.editar-perfil', $data);
                }
            }else{   // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');

            }
        }
        catch (Exception $e) {
            return view('errors.error')->with('error',$e->getMessage());
        }

	}

    public function verCursos()
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_cursos_part')) {// Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['cursos'] = [];
                $data['fechas'] = [];
                $participante = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); //
                $data['cursos_'] = ParticipanteCurso::where('id_participante', '=', $participante[0]->id)->get();
                if ($data['cursos_']->count()) {
                    foreach ($data['cursos_'] as $index => $curso) {
                        $cursos  = Curso::where('id', '=', $curso->id_curso)->get();
                        $data['cursos'][$index] = $cursos;
//                        dd($cursos[0]->fecha_inicio);
                        $data['inicio'][$index] = new DateTime($cursos[0]->fecha_inicio);
                        $data['fin'][$index] = new DateTime($cursos[0]->fecha_fin);
                        $data['seccion'][$index] = $curso->seccion;
                        $tipos = TipoCurso::where('id', '=', $cursos[0]->id_tipo)->get();
                        $data['tipo_curso'][$index] = $tipos[0]->nombre;
                    }
//                    dd($);
                }

                return view('participantes.cursos.cursos', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function verPagosCurso($id_curso) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_pagos')) {// Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['curso'] = $curso = Curso::find($id_curso);
                $data['tipo'] = TipoCurso::find($curso->id_tipo);
                $participante = Participante::where('id_usuario', '=', $usuario_actual->id)->get();
                $data['pagos'] = $pagos = Pago::where('id_curso', '=', $id_curso)
                                                ->where('id_participante', '=', $participante[0]->id)->paginate(5);
                $data['tipo_pago'] = ModalidadPago::all()->lists('nombre','id');
                $data['bancos'] = Banco::all()->lists('nombre','id');
                $total = 0;
                $data['completo'] = true;
                if($pagos->count()){
                    foreach($data['pagos'] as $pago){
                        $total = $total + $pago->monto;
                        $pago['modalidad'] = ModalidadPago::find($pago->id_modalidad_pago);
                        $pago['banco'] = Banco::find($pago->id_banco);
                        if($pago->aprobado){
                            $pago['estatus'] = 'Aprobado';
                        }else{
                            $pago['estatus'] = 'En espera';
                        }
                    }
//                    dd($total);
                    if($total < $curso->costo){
                        $data['completo'] = false;
                        $pagos_restantes = 3 - $pagos->count();
                        $data['pagos_restantes'] = 3 - $pagos->count();
                        if($pagos_restantes >= 3){
                            Session::set('error', 'Usted ya realizó 3 cuotas y no ha cancelado la totalidad del curso, comuniquese con el centro');
                            return $this->index();
                        }
                    }else{
                        $data['completo'] = true;
                        $data['pagos_restantes'] = 0;
                    }
                }else{
                    $data["completo"] = false;
                }
//                if($data['completo']){
//                    $data['pagos_restantes'] = 0;
//                }else{
//                    $data['pagos_restantes'] = 3 - $pagos->count();
//                }

                return view('participantes.cursos.pagos.pagos', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function reciboPagoCurso($id_curso, $id_pago) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_pagos')) {// Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['curso'] = $curso = Curso::find($id_curso);
                $data['tipo'] = TipoCurso::find($curso->id_tipo);
                $data['pago'] = Pago::find($id_pago);
                $data['modalidad'] = ModalidadPago::find($data['pago']->id_modalidad_pago);
                $data['banco'] = Banco::find($data['pago']->id_banco);
                $data['participante'] = Participante::where('id_usuario', '=', $usuario_actual->id)->get();

                if($data['pago']->count()){
                    $pdf = PDF::loadView('participantes.cursos.pagos.recibo',$data);
                    return $pdf->stream("Recibo de pago-".$curso->nombre.".pdf", array('Attachment'=>0));
                }else{
                    Session::set('error', 'Disculpe, no existen participantes en el modulo '.$data['modulo']->nombre);
                    return $this->index();
                }

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function generarPagoCurso($id_curso) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_pagos')) {// Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['curso'] = $curso = Curso::find($id_curso);
                $data['tipo'] = TipoCurso::find($curso->id_tipo);
                $participante = Participante::where('id_usuario', '=', $usuario_actual->id)->get();
                $data['pagos'] = $pagos = Pago::where('id_curso', '=', $id_curso)
                                                ->where('id_participante', '=', $participante[0]->id)->get();
                $data['tipo_pago'] = ModalidadPago::all()->lists('nombre','id');
                $data['bancos'] = Banco::all()->lists('nombre','id');
                $total = 0;
                $data['completo'] = true;
                if($pagos->count()){
                    foreach($data['pagos'] as $pago){
                        $total = $total + $pago->monto;
                        $pago['modalidad'] = ModalidadPago::find($pago->id_modalidad_pago);
                        $pago['banco'] = Banco::find($pago->id_banco);
                        if($pago->aprobado){
                            $pago['estatus'] = 'Aprobado';
                        }else{
                            $pago['estatus'] = 'En espera';
                        }
                    }
                    if($total < $curso->costo){
                        $data['completo'] = false;
                        $data['pagos_restantes'] = 3 - $pagos->count();
                    }else{
                        $data['completo'] = true;
                        $data['pagos_restantes'] = 0;
                        Session::set('mensaje', 'Usted ya se encuentra al día con los pagos de la actividad '.$curso->nombre);
                        return view('participantes.cursos.pagos.pagos', $data);
                    }
                }

                return view('participantes.cursos.pagos.nuevo', $data);


            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }
    public function guardarPagoCurso(PagoRequest $request, $id_curso) {
            try{
                //Verificación de los permisos del usuario para poder realizar esta acción
                $usuario_actual = Auth::user();
                if($usuario_actual->foto != null) {
                    $data['foto'] = $usuario_actual->foto;
                }else{
                    $data['foto'] = 'foto_participante.png';
                }

                if($usuario_actual->can('ver_pagos')) {// Si el usuario posee los permisos necesarios continua con la acción
                    $data['errores'] = '';
                    $data['curso'] = $curso = Curso::find($id_curso);
                    $data['tipo'] = TipoCurso::find($curso->id_tipo);
                    $participante = Participante::where('id_usuario', '=', $usuario_actual->id)->get();
                    $data['pagos'] = $pagos = Pago::where('id_curso', '=', $id_curso)
                        ->where('id_participante', '=', $participante[0]->id)->get();

                    $id_modalidad = Input::get('tipo_pago');
                    if($id_modalidad == 0){
                        Session::set('error', 'Debe seleccionar una modalidad de pago');
                        return $this->generarPagoCurso($id_curso);
                    }

                    $id_banco = Input::get('banco');
                    if($id_banco == 0){
                        Session::set('error', 'Debe seleccionar el banco');
                        return $this->generarPagoCurso($id_curso);
                    }

                    $total = 0;
                    $restante = 0;
                    $data['completo'] = true;
                    if($pagos->count()){
                        foreach($data['pagos'] as $pago){
                            $total = $total + $pago->monto;
                            $pago['modalidad'] = ModalidadPago::find($pago->id_modalidad_pago);
                            if($pago->aprobado){
                                $pago['estatus'] = 'Aprobado';
                            }else{
                                $pago['estatus'] = 'En espera';
                            }
                        }
                        if($total < $curso->costo){
                            $data['completo'] = false;
                        }
                        $restante = $curso->costo - $total;
                    }

                    $pagos_restantes = 3 - $pagos->count();
                    if($pagos_restantes < 0){
                        Session::set('error', 'Cominiquese con el centro ya que posee más de 3 cuotas de pago');
                        return $this->generarPagoCurso($id_curso);
                    }
                    if($data['completo'] == false && $pagos_restantes == 0){
                        Session::set('error', 'Comuniquese con el centro ya que posee el número máximo de cuotas de pago');
                        return $this->generarPagoCurso($id_curso);
                    }
                    if($data['completo'] == true){
                        $data['pagos_restantes'] = 0;
                        Session::set('mensaje', 'Usted ya se encuentra al día con los pagos de la actividad '.$curso->nombre);
                        return view('participantes.cursos.pagos.pagos', $data);
                    }elseif($pagos_restantes >= 2){
                        $data['pagos_restantes'] = 3 - $pagos->count();
                    }elseif($pagos_restantes == 1){
                        if($request->monto < $restante){
                            Session::set('error', 'Le queda una última cuota y el monto de su nuevo pago es menor al monto restante. Debe realizar el pago por '.$restante.' Bs');
                            return $this->generarPagoCurso($id_curso);
                        }elseif($request->monto > $restante) {
                            Session::set('error', 'Le queda una última cuota y el monto de su nuevo pago es mayor al monto restante. Debe realizar el pago por ' . $restante . ' Bs');
                            return $this->generarPagoCurso($id_curso);
                        }else{
                            $data['pagos_restantes'] = 0;
                        }
                    }

                    $pago = new Pago();
                    $pago->id_participante = $participante[0]->id;
                    $pago->id_curso = $id_curso;
                    $pago->monto = $request->monto;
                    $pago->id_modalidad_pago = $id_modalidad;
                    $pago->aprobado = false;
                    $pago->numero_pago = $request->numero_pago;
                    $pago->id_banco = $request->banco;
                    $pago->save();

                    $data['pago_'] = $pago;
                    $data['participante'] = Participante::find($pago->id_participante);
                    $data['email'] = $usuario_actual->email;
                    $data['cursos'] = Curso::find($pago->id_curso);
                    if($pago->save()){
                        Mail::send('emails.pago-aprobado', $data, function ($message) use ($data) {
                            $message->subject('CIETE - Pago aprobado')
                                ->to($data['email'], 'CIETE')
                                ->replyTo($data['email']);
                        });
                        Session::set('mensaje', 'Pago guardado con éxito');
                        return $this->verPagosCurso($id_curso);
                    }else{
                        Session::set('error', 'Haocurrido un error inesperado');
                        return $this->verPagosCurso($id_curso);
                    }

                }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                    return view('errors.sin_permiso');
                }
            }
            catch (Exception $e) {

                return view('errors.error')->with('error',$e->getMessage());
            }
        }

    public function verModulosCurso($id_curso)
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_notas_part')) {// Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['curso'] = Curso::find($id_curso);
                $data['modulos'] = Modulo::where('id_curso','=', $id_curso)->get();

                return view('participantes.cursos.modulos', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function verNotasCurso($id, $modulo)
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_notas_part')) {// Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $id_part = Participante::where('id_usuario', '=', $usuario_actual->id)->get();
                $curso_part = ParticipanteCurso::where('id_participante', '=', $id_part[0]->id)->where('id_curso', '=', $id)->get();
                $data['curso'] = Curso::find($id);
                $data['modulo'] = Modulo::find($modulo);
                $data['notas'] = Nota::where('id_participante_curso', '=', $curso_part[0]->id)
                                    ->where('id_modulo', '=', $modulo)->get();
                if($data['notas']->count()) {
                    $data['promedio'] = 0;
                    $porcentaje = 0;
                    foreach ($data['notas'] as $nota) {
                        $calif = $nota->calificacion;
                        $porcent = $nota->porcentaje;
                        $porcentaje =  ($porcentaje + $porcent);
                        $data['promedio'] = $data['promedio'] + ($calif * ($porcent / 100));
                    }
                    $data['porcentaje'] =  100 - $porcentaje;
                }

                return view('participantes.cursos.notas', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function verWebinars()
    {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('ver_cursos_part')) {// Si el usuario posee los permisos necesarios continua con la acción

                $data['errores'] = '';
                $data['webinars'] = [];
                $data['fechas'] = [];
                $participante = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); //
                $webinars_ = ParticipanteWebinar::where('id_participante', '=', $participante[0]->id)->get();
                if ($webinars_->count()) {
                    foreach ($webinars_ as $index => $web) {
                        $webinars  = Webinar::where('id', '=', $web->id_webinar)->get();
                        $data['webinars'][$index] = $webinars;
                        $data['seccion'][$index] = $web->seccion;
                        $data['inicio'][$index] = new DateTime($webinars[0]->fecha_inicio);
                        $data['fin'][$index] = new DateTime($webinars[0]->fecha_fin);
                    }

                }

                return view('participantes.ver-webinars', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }


    public function cambiarImagen()
    {
        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_perfil_part')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['datos'] = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del participante
                $data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del participante;
                $data['paises'] = Pais::all()->lists('nombre_mostrar', 'id');
                $data['estados'] = Estado::all()->lists('estado','id_estado');
                Session::flash('imagen', 'yes');
                return view('participantes.editar-perfil', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        } catch (Exception $e) {
            return view('errors.error')->with('error', $e->getMessage());
        }
    }

    public function procesarImagen() {

        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_perfil_part')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['ruta'] = Input::get('rutas');
                $data['errores'] = '';
                $data['datos'] = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del participante
                $data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del participante;
                $data['paises'] = Pais::all()->lists('nombre_mostrar', 'id');
                $data['estados'] = Estado::all()->lists('estado','id_estado');
                Session::flash('imagen', null);
                Session::flash('cortar', 'yes');
                return view('participantes.editar-perfil', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        } catch (Exception $e) {
            return view('errors.error')->with('error', $e->getMessage());
        }

    }

    public function cambiarArchivoDi()
    {
        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_perfil_part')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['datos'] = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del participante
                $data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del participante;
                $data['paises'] = Pais::all()->lists('nombre_mostrar', 'id');
                $data['estados'] = Estado::all()->lists('estado','id_estado');
                Session::flash('di_f', 'yes');
                return view('participantes.editar-perfil', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        } catch (Exception $e) {
            return view('errors.error')->with('error', $e->getMessage());
        }
    }
    public function cambiarArchivoT()
    {
        try {

            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('editar_perfil_part')) {  // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['datos'] = Participante::where('id_usuario', '=', $usuario_actual->id)->get(); // Se obtienen los datos del participante
                $data['email']= User::where('id', '=', $usuario_actual->id)->get(); // Se obtiene el correo principal del participante;
                $data['paises'] = Pais::all()->lists('nombre_mostrar', 'id');
                $data['estados'] = Estado::all()->lists('estado','id_estado');
                Session::flash('titulo_', 'yes');
                return view('participantes.editar-perfil', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        } catch (Exception $e) {
            return view('errors.error')->with('error', $e->getMessage());
        }
    }

    public function verPdf($doc) {
        try {
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if ($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            } else {
                $data['foto'] = 'foto_participante.png';
            }

            if ($usuario_actual->can('editar_perfil_part')) {
                $data['errores'] = '';
                $data['busq_'] = false;
                $path = public_path() . '/documentos/participantes/' . $doc;

                return Response::make(file_get_contents($path), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; ' . $doc,
                ]);
            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

}
