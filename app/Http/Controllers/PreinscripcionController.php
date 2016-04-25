<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\PreinscripcionRequest;
use App\Http\Requests\PreinscripcionWebRequest;

use App\Models\Curso;
use App\Models\ModalidadPago;
use App\Models\Preinscripcion;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use File;

use DateTime;
use Auth;
use Exception;
use App\Models\TipoCurso;

use DB;
use Input;
use Mail;

class PreinscripcionController extends Controller {

    //
    public function showProcedimiento(){
        $data['errores'] = '';
        return view('preinscripcion.procedimiento', $data);
    }


    public function index(){
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('activar_preinscripcion')) {    // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['cursos'] = Curso::where('curso_activo', '=', true)
                                        ->where('fecha_inicio', '>', new DateTime('today'))
                                        ->orderBy('created_at')->get();

                foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsula o Diplomado)
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    $curso['tipo_curso'] = $tipo[0]->nombre;
                    $curso['inicio'] = new DateTime($curso->fecha_inicio);
                    $curso['fin'] = new DateTime($curso->fecha_fin);

                }

                return view('preinscripcion.preinscripcion-cursos', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function indexWeb(){
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('activar_preinscripcion')) {    // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['webinars'] = Webinar::where('webinar_activo', '=', true)
                    ->where('fecha_inicio', '>', new DateTime('today'))
                    ->orderBy('created_at')->get();

                foreach ($data['webinars'] as $web) {
                    $web['inicio'] = new DateTime($web->fecha_inicio);
                    $web['fin'] = new DateTime($web->fecha_fin);

                }

                return view('preinscripcion.preinscripcion-webinars', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }


    public function mostrarPreinscripcionCurso(){

        $data['errores'] = '';
        $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');
        $data['tipo_pago'] = ModalidadPago::all()->lists('nombre','id');

        return view('preinscripcion.curso', $data);
    }

    public function mostrarPreinscripcionWebinar(){

        $data['errores'] = '';
        $data['webinars'] = Webinar::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');

        return view('preinscripcion.webinar', $data);
    }

    public function storePreinscripcionCurso(PreinscripcionRequest $request){

        try {

            $preins = new Preinscripcion();
            $cours = new Curso();
            $data['errores'] = '';
            $id_curso = Input::get('curso');
            $id_modalidad = Input::get('tipo_pago');

            if($id_curso == 0){
                $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre', 'id');
                $data['tipo_pago'] = ModalidadPago::all()->lists('nombre','id');
                Session::set('error', 'Debe seleccionar un curso de la lista');
                return view('preinscripcion.curso', $data);
            }
            if($id_modalidad == 0){
                $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre', 'id');
                $data['tipo_pago'] = ModalidadPago::all()->lists('nombre','id');
                Session::set('error', 'Debe seleccionar una modalidad de pago');
                return view('preinscripcion.curso', $data);
            }

            //  Se valida que los archivos esten en formato PDF
//            $validar_di = Validator::make(array('cedula' => $request->file('cedula')), array('cedula' => 'mimes:pdf'));
//            $validar_titulo = Validator::make(array('titulo' => $request->file('titulo')), array('titulo' => 'mimes:pdf'));
//            $validar_recibo = Validator::make(array('recibo' => $request->file('recibo')), array('recibo' => 'mimes:pdf'));
//            if ($validar_di->fails() || $validar_titulo->fails() || $validar_recibo->fails()) {
//                $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');
//                $data['tipo_pago'] = ModalidadPago::all()->lists('nombre','id');
//                Session::set('error','Los archivos deben estar en formato PDF');
//                return view('preinscripcion.curso', $data);
//
//            }

            $max= $cours->maxCuposCurso($id_curso); //obtengo el máximo número de participantes que puede tener un curso
            $cant = $preins->cantParticipantes($id_curso);

            if($cant < $max){
                $hay = Preinscripcion::all();
                if($hay->count()){
                    $existe = Preinscripcion::where('id_curso','=', $request->curso)
                        ->where('tipo', '=', 'curso')
                        ->where('nombre', '=', $request->nombre)
                        ->where('apellido', '=', $request->apellido)
                        ->where('di', '=', $request->di)->get();
                    if(!($existe->count())) {
                        $create2 = Preinscripcion::findOrNew($request->id);
                        $create2->id_curso = $request->curso;
                        $create2->nombre = $request->nombre;
                        $create2->apellido = $request->apellido;
                        $create2->di = $request->di;
                        $create2->email = $request->email;
                        $create2->id_modalidad_pago = $request->tipo_pago;
                        $create2->monto = $request->monto;
                        $create2->numero_pago = $request->numero_pago;
                        $curso = Curso::find($id_curso);
                        $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                        $create2->tipo = $tipo[0]->nombre;
                        $create2->save();

                        // Se crean los nombres de los archivos que se van a guardar y se guardan en la BD
//                        $nombreDI = 'D_identidad_' . date('dmY') . '_' . date('His') . '.pdf';
//                        $nombreTitulo = 'Titulo_' . date('dmY') . '_' . date('His') . '.pdf';
//                        $nombreRecibo = 'Recibo_' . date('dmY') . '_' . date('His') . '.pdf';
//                        $create2->documento_identidad = $nombreDI;
//                        $create2->titulo = $nombreTitulo;
//                        $create2->recibo = $nombreRecibo;
//                        $create2->save();
//
//                        // se guardan los archivos PDF en la carpeta correcta
//                        $pdfDI = $request->file('cedula');
//                        $pdfTitulo = $request->file('titulo');
//                        $pdfRecibo = $request->file('recibo');
//                        Storage::put('/documentos/preinscripciones_pdf'.$nombreDI, \File::get($pdfDI ));
//                        Storage::put('/documentos/preinscripciones_pdf/'.$nombreTitulo, \File::get($pdfTitulo) );
//                        Storage::put('/documentos/preinscripciones_pdf/'.$nombreRecibo, \File::get($pdfRecibo ));


                        $data['nombre'] = $request->nombre;
                        $data['apellido'] = $request->apellido;
                        $data['curso'] = $preins->getCursoName($id_curso); // aquí se retorna el nombre del curso
                        $data['email'] = $request->email;

                        Mail::send('emails.preinscripcion', $data, function ($message) use ($data) {
                            $message->subject('CIETE - Preinscripcion')
                                ->to($data['email'], 'CIETE')
                                ->replyTo($data['email']);
                        });

                        $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre', 'id');
                        $data['tipo_pago'] = ModalidadPago::all()->lists('nombre','id');
                        Session::set('mensaje', 'Le hemos enviado un mensaje de confirmación a su correo');
                        return view('preinscripcion.curso', $data);
                    }else{
                        $curso = Curso::find($id_curso);
                        $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');
                        $data['tipo_pago'] = ModalidadPago::all()->lists('nombre','id');
                        Session::set('error', 'Usted ya se encuentra inscrito en el curso '.$curso->nombre);
                        return view('preinscripcion.curso', $data);
                    }

                }else{
                    $create2 = Preinscripcion::findOrNew($request->id);
                    $create2->id_curso = $request->curso;
                    $create2->nombre = $request->nombre;
                    $create2->apellido = $request->apellido;
                    $create2->di = $request->di;
                    $create2->email = $request->email;
                    $create2->id_modalidad_pago = $request->tipo_pago;
                    $create2->monto = $request->monto;
                    $create2->numero_pago = $request->numero_pago;
                    $curso = Curso::find($id_curso);
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    $create2->tipo = $tipo[0]->nombre;
                    $create2->save();

//                    // Se crean los nombres de los archivos que se van a guardar y se guardan en la BD
//                    $nombreDI = 'D_identidad_' . date('dmY') . '_' . date('His') . '.pdf';
//                    $nombreTitulo = 'Titulo_' . date('dmY') . '_' . date('His') . '.pdf';
//                    $nombreRecibo = 'Recibo_' . date('dmY') . '_' . date('His') . '.pdf';
//                    $create2->documento_identidad = $nombreDI;
//                    $create2->titulo = $nombreTitulo;
//                    $create2->recibo = $nombreRecibo;
//                    $create2->save();
//
//                    // se guardan los archivos PDF en la carpeta correcta
//                    $pdfDI = $request->file('cedula');
//                    $pdfTitulo = $request->file('titulo');
//                    $pdfRecibo = $request->file('recibo');
//                    Storage::put('/documentos/preinscripciones_pdf/'.$nombreDI, \File::get($pdfDI) );
//                    Storage::put('/documentos/preinscripciones_pdf/'.$nombreTitulo, \File::get($pdfTitulo) );
//                    Storage::put('/documentos/preinscripciones_pdf/'.$nombreRecibo, \File::get($pdfRecibo) );



                    $data['nombre'] = $request->nombre;
                    $data['apellido'] = $request->apellido;
                    $data['curso'] = $preins->getCursoName($id_curso); // aquí se retorna el nombre del curso
                    $data['email'] = $request->email;

                    Mail::send('emails.preinscripcion', $data, function ($message) use ($data) {
                        $message->subject('CIETE - Preinscripcion')
                            ->to($data['email'], 'CIETE')
                            ->replyTo($data['email']);
                    });
                    $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre', 'id');
                    $data['tipo_pago'] = ModalidadPago::all()->lists('nombre','id');
                    Session::set('mensaje', 'Le hemos enviado un mensaje de confirmación a nsu correo.');
                    return view('preinscripcion.curso', $data);
                }
            }else{
                $curso = Curso::find($id_curso);
                $curso->activo_preinscripcion = false;//Se desactiva el curso
                $curso->save(); // se guarda
                $data['cursos'] = Curso::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');
                $data['tipo_pago'] = ModalidadPago::all()->lists('nombre','id');
                Session::set('error', 'Ya no quedan cupos disponibles para el curso '.$curso->nombre);
                return view('preinscripcion.curso', $data);

            }

        } catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }

    }

    public function storePreinscripcionWebinar(PreinscripcionWebRequest $request){

        try {

            $preins = new Preinscripcion();
            $cours = new Webinar();
            $data['errores'] = '';
            $id = Input::get('curso');
            if($id == 0){
                $data['webinars'] = Webinar::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre', 'id');
                Session::set('error', 'Debe seleccionar un webinar de la lista');
                return view('preinscripcion.webinar', $data);
            }
            $max= $cours->maxCuposWeb($id); //obtengo el máximo número de participantes que puede tener un curso
            $cant = $preins->cantParticipantes($id);

            if($cant < $max){
                $hay = Preinscripcion::all();
                if($hay->count()){
                    $existe = Preinscripcion::where('id_curso','=', $request->curso)
                        ->where('tipo', '=', 'webinar')
                        ->where('nombre', '=', $request->nombre)
                        ->where('apellido', '=', $request->apellido)->get();
                    if(!($existe->count())) {
                        $create2 = Preinscripcion::findOrNew($request->id);
                        $create2->id_curso = $request->curso;
                        $create2->nombre = $request->nombre;
                        $create2->apellido = $request->apellido;
                        $create2->di = $request->di;
                        $create2->email = $request->email;
                        $create2->monto = '';
                        $create2->id_modalidad_pago = 1;
                        $create2->numero_pago = '';
                        $create2->tipo = 'Webinar';

                        $create2->save();

                        $data['nombre'] = $request->nombre;
                        $data['apellido'] = $request->apellido;

                        $data['curso'] = $preins->getWebName($id); // aquí se retorna el nombre del webinar

                        $data['email'] = $request->email;

                        Mail::send('emails.preinscripcion', $data, function ($message) use ($data) {
                            $message->subject('CIETE - Preinscripcion')
                                ->to($data['email'], 'CIETE')
                                ->replyTo($data['email']);
                        });
                        $data['webinars'] = Webinar::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre', 'id');

                        Session::set('mensaje', 'Le hemos enviado un mensaje de confirmación a su correo.');
                        return view('preinscripcion.webinar', $data);
                    }else{
                        $web = Webinar::find($id);
                        $data['webinars'] = Webinar::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');

                        Session::set('error', 'Usted ya se encuentra inscrito en el webinar '.$web->nombre);
                        return view('preinscripcion.webinar', $data);
                    }

                }else{
                    $create2 = Preinscripcion::findOrNew($request->id);
                    $create2->id_curso = $request->curso;
                    $create2->nombre = $request->nombre;
                    $create2->apellido = $request->apellido;
                    $create2->di = $request->di;
                    $create2->email = $request->email;
                    $create2->monto = '';
                    $create2->id_modalidad_pago = 1;
                    $create2->numero_pago = '';
                    $create2->tipo = 'Webinar';

                    $create2->save();

                    $data['nombre'] = $request->nombre;
                    $data['apellido'] = $request->apellido;

                    $data['curso'] = $preins->getCursoName($id); // aquí se retorna el nombre del webinar

                    $data['email'] = $request->email;

                    Mail::send('emails.preinscripcion', $data, function ($message) use ($data) {
                        $message->subject('CIETE - Preinscripcion')
                            ->to($data['email'], 'CIETE')
                            ->replyTo($data['email']);
                    });
                    $data['cursos'] = Webinar::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre', 'id');

                    Session::set('mensaje', 'Le hemos enviado un mensaje de confirmación a nsu correo.');
                    return view('preinscripcion.webinar', $data);
                }
            }else{
                $web = Webinar::find($id);
                $web->activo_preinscripcion = false;//Se desactiva el curso
                $web->save(); // se guarda
                $data['webinars'] = Webinar::where('activo_preinscripcion', true)->orderBy('nombre')->lists('nombre','id');

                Session::set('error', 'Ya no quedan cupos disponibles para el curso '.$web->nombre);
                return view('preinscripcion.webinar', $data);

            }

        } catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }

    }

    public function activarPreinscripcion($id){
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('activar_preinscripcion')) {// Si el usuario posee los permisos necesarios continua con la acción
                // Se obtienen los datos del curso que se desea eliminar
                $curso = Curso::find($id);
                //Se desactiva el curso
                $curso->activo_preinscripcion = true;
                $curso->save(); // se guarda

                $data['errores'] = '';
                $data['cursos'] = Curso::where('curso_activo', '=', true)
                    ->where('fecha_inicio', '>', new DateTime('today'))
                    ->orderBy('created_at')->get();

                foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsula o Diplomado)
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    $curso['tipo_curso'] = $tipo[0]->nombre;
                    $curso['inicio'] = new DateTime($curso->fecha_inicio);
                    $curso['fin'] = new DateTime($curso->fecha_fin);

                }


                return view('preinscripcion.preinscripcion-cursos', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function desactivarPreinscripcion($id){
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('desactivar_preinscripcion')) {    // Si el usuario posee los permisos necesarios continua con la acción
                // Se obtienen los datos del curso que se desea eliminar
                $curso = Curso::find($id);
                //Se desactiva el curso
                $curso->activo_preinscripcion = false;
                $curso->save(); // se guarda

                $data['errores'] = '';
                $data['cursos'] = Curso::where('curso_activo', '=', true)
                    ->where('fecha_inicio', '>', new DateTime('today'))
                    ->orderBy('created_at')->get();

                foreach ($data['cursos'] as $curso) {   // Se asocia el tipo a cada curso (Cápsula o Diplomado)
                    $tipo = TipoCurso::where('id', '=', $curso->id_tipo)->get();
                    $curso['tipo_curso'] = $tipo[0]->nombre;
                    $curso['inicio'] = new DateTime($curso->fecha_inicio);
                    $curso['fin'] = new DateTime($curso->fecha_fin);

                }


                return view('preinscripcion.preinscripcion-cursos', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function activarPreinscripcionWeb($id){
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('activar_preinscripcion')) {// Si el usuario posee los permisos necesarios continua con la acción
                // Se obtienen los datos del curso que se desea eliminar
                $webinar = Webinar::find($id);
                //Se desactiva el curso
                $webinar->activo_preinscripcion = true;
                $webinar->save(); // se guarda

                $data['errores'] = '';
                $data['webinars'] = Webinar::where('webinar_activo', '=', true)
                    ->where('fecha_inicio', '>', new DateTime('today'))
                    ->orderBy('created_at')->get();

                foreach ($data['webinars'] as $web) {
                    $web['inicio'] = new DateTime($web->fecha_inicio);
                    $web['fin'] = new DateTime($web->fecha_fin);

                }

                return view('preinscripcion.preinscripcion-webinars', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function desactivarPreinscripcionWeb($id){
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('desactivar_preinscripcion')) {    // Si el usuario posee los permisos necesarios continua con la acción
                // Se obtienen los datos del webinar que se desea eliminar
                $webinar = Webinar::find($id);
                //Se desactiva el curso
                $webinar->activo_preinscripcion = false;
                $webinar->save(); // se guarda

                $data['errores'] = '';
                $data['webinars'] = Webinar::where('webinar_activo', '=', true)
                    ->where('fecha_inicio', '>', new DateTime('today'))
                    ->orderBy('created_at')->get();

                foreach ($data['webinars'] as $web) {
                    $web['inicio'] = new DateTime($web->fecha_inicio);
                    $web['fin'] = new DateTime($web->fecha_fin);

                }

                return view('preinscripcion.preinscripcion-webinars', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }
}
