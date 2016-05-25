<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Banco;
use App\Models\Curso;
use App\Models\Pago;
use App\Models\ModalidadPago;
use App\Models\Participante;
use App\Models\ParticipanteCurso;
use App\Models\ParticipanteWebinar;
use App\Models\Profesor;
use App\Models\Role;
use App\Models\Webinar;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Response;
use Mail;

use App\Models\Preinscripcion;
use Illuminate\Http\Request;

class InscripcionController extends Controller {


	public function index() {
		try{

			//Verificación de los permisos del usuario para poder realizar esta acción
			$usuario_actual = Auth::user();
			if($usuario_actual->foto != null) {
				$data['foto'] = $usuario_actual->foto;
			}else{
				$data['foto'] = 'foto_participante.png';
			}

			if($usuario_actual->can('activar_inscripcion')) {    // Si el usuario posee los permisos necesarios continua con la acción
				$data['errores'] = '';
				$data['busq_'] = false;
				$data['usuarios'] = Preinscripcion::all();
                foreach ($data['usuarios'] as $usuario) {
                    if($usuario->tipo != 'Webinar') {
                        $curso = Curso::where('id', '=', $usuario->id_curso)->get();
                    }else{
                        $curso = Webinar::where('id', '=', $usuario->id_curso)->get();
                    }
                    $modo = ModalidadPago::where('id', '=', $usuario->id_modalidad_pago)->get();
                    $usuario['curso_nombre'] = $curso[0]->nombre;
                    $usuario['modalidad'] = $modo[0]->nombre;
                    $usuario['banco'] = Banco::find($usuario->id_banco);

                }
				$data['tipos'] = ['Cápsula', 'Diplomado', 'Webinar'];

				return view('inscripciones.inscripciones', $data);

			}else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

				return view('errors.sin_permiso');
			}
		}
		catch (Exception $e) {

			return view('errors.error')->with('error',$e->getMessage());
		}
	}


	public function buscarInscripcion()
	{
		try{
			//Verificación de los permisos del usuario para poder realizar esta acción
			$usuario_actual = Auth::user();
			if($usuario_actual->foto != null) {
				$data['foto'] = $usuario_actual->foto;
			}else{
				$data['foto'] = 'foto_participante.png';
			}
			if($usuario_actual->can('ver_lista_cursos')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['tipos'] = ['Cápsula', 'Diplomado', 'Webinar'];
				$data['usuarios'] = [];
				$data['errores'] = '';
				$data['busq_'] = true;
				$val = '';
                $data['usuarios'] = Preinscripcion::all();
                foreach ($data['usuarios'] as $usuario) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                    if($usuario->tipo != 'Webinar') {
                        $curso = Curso::where('id', '=', $usuario->id_curso)->get();
                    }else{
                        $curso = Webinar::where('id', '=', $usuario->id_curso)->get();
                    }
                    $modo = ModalidadPago::where('id', '=', $usuario->id_modalidad_pago)->get();
                    $usuario['curso_nombre'] = $curso[0]->nombre;
                    $usuario['modalidad'] = $modo[0]->nombre;
                    $usuario['banco'] = Banco::find($usuario->id_banco);

                }
//                $data['tipos'] = ['Diplomado', 'Cápsula', 'Webinar'];
				$param = Input::get('parametro');
				if($param == '0'){
//					$data['usuarios'] = Preinscripcion::all();
//                    foreach ($data['usuarios'] as $usuario) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
//                        if($usuario->tipo != 'Webinar') {
//                            $curso = Curso::where('id', '=', $usuario->id_curso)->get();
//                        }else{
//                            $curso = Webinar::where('id', '=', $usuario->id_curso)->get();
//                        }
//                        $modo = ModalidadPago::where('id', '=', $usuario->id_modalidad_pago)->get();
//                        $usuario['curso_nombre'] = $curso[0]->nombre;
//                        $usuario['modalidad'] = $modo[0]->nombre;
//
//                    }
					Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
					return view('inscripciones.inscripciones', $data);
				}
				if ($param != 'tipo'){
					if (empty(Input::get('busqueda'))) {
//						$data['usuarios'] = Preinscripcion::all();
//                        foreach ($data['usuarios'] as $usuario) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
//                            if($usuario->tipo != 'Webinar') {
//                                $curso = Curso::where('id', '=', $usuario->id_curso)->get();
//                            }else{
//                                $curso = Webinar::where('id', '=', $usuario->id_curso)->get();
//                            }
//                            $modo = ModalidadPago::where('id', '=', $usuario->id_modalidad_pago)->get();
//                            $usuario['curso_nombre'] = $curso[0]->nombre;
//                            $usuario['modalidad'] = $modo[0]->nombre;
//
//                        }
						Session::set('error', 'Coloque el elemento que desea buscar');
						return view('inscripciones.inscripciones', $data);
					}else{
						$busq = Input::get('busqueda');
					}
				}else{
					$busq = Input::get('busqu');
				}
				if(($param != 'tipo')){
					$data['usuarios'] = Preinscripcion::where($param, 'ilike', '%'.$busq.'%')
						->orderBy('created_at')->get();
				}elseif($param == 'tipo'){
					if($busq == 0){
						$val = 'Diplomado';
					}elseif($busq == 1){
						$val = 'Cápsula';
					}else{
                        $val = 'Webinar';
                    }
					$data['usuarios'] = Preinscripcion::where('tipo', '=', $val)
						->orderBy('created_at')->get();
                    foreach ($data['usuarios'] as $usuario) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                        if($usuario->tipo != 'Webinar') {
                            $curso = Curso::where('id', '=', $usuario->id_curso)->get();
                        }else{
                            $curso = Webinar::where('id', '=', $usuario->id_curso)->get();
                        }
                        $modo = ModalidadPago::where('id', '=', $usuario->id_modalidad_pago)->get();
                        $usuario['curso_nombre'] = $curso[0]->nombre;
                        $usuario['modalidad'] = $modo[0]->nombre;
                        $usuario['banco'] = Banco::find($usuario->id_banco);
                    }
				}
				return view('inscripciones.inscripciones', $data);

			}else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
				return view('errors.sin_permiso');
			}
		}
		catch (Exception $e) {

			return view('errors.error')->with('error',$e->getMessage());
		}
	}


	public function store()
	{
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('activar_inscripcion')) {    // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['busq_'] = false;
                $actividad = '';
                $secc = ['1','2','3','4','5','6','7','8','9'];
                $id = Input::get('val');
//                dd($id);
                $usuario = Preinscripcion::find($id);
                $existe = User::where('email', '=', $usuario->email)->get();// se verifica si el usuario ya está registrado

                $data['usuarios'] = Preinscripcion::all();
                foreach ($data['usuarios'] as $usr) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                    if($usr->tipo != 'Webinar') {
                        $curso = Curso::where('id', '=', $usr->id_curso)->get();
                    }else{
                        $curso = Webinar::where('id', '=', $usr->id_curso)->get();
                    }
                    $modo = ModalidadPago::where('id', '=', $usr->id_modalidad_pago)->get();
                    $usr['curso_nombre'] = $curso[0]->nombre;
                    $usr['modalidad'] = $modo[0]->nombre;
                    $usr['banco'] = Banco::find($usr->id_banco);
                }
                $data['tipos'] = ['Cápsula', 'Diplomado', 'Webinar'];

                if($existe->count()){
                    $es_profe = Profesor::where('id_usuario', '=', $existe[0]->id)->get();
                    if($es_profe->count()){
                        Session::set('error', 'El usuario está registrado como profesor, debe inscribirse con otro correo');
                        return view('inscripciones.inscripciones', $data);
                    }
                }

                if($usuario->tipo == 'Diplomado' || $usuario->tipo == 'Cápsula'){
                    $actividad = Curso::find($usuario->id_curso);
                    $data['tipo'] = 'curso';
                }elseif($usuario->tipo == 'Webinar'){
                    $actividad = Webinar::find($usuario->id_curso);
                    $data['tipo'] = 'webinar';
                }

                $data['email'] = $usuario->email;
                $data['nombre'] = $usuario->nombre;
                $data['apellido'] = $usuario->apellido;
                $data['curso'] = $actividad->nombre;
                $tam = 6;
                $clave = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $tam);
                $data['clave'] = $clave;

                if($usuario->tipo == 'Diplomado' || $usuario->tipo == 'Cápsula'){
                    if(ParticipanteCurso::all()->count() != 0) {
                        $max_secc = $actividad->max;
                        $todo = ParticipanteCurso::where('id_curso', '=', $actividad->id)->orderBy('created_at')->get();
                        $todo = end($todo);
                        $ultimo = end($todo);
                        $cuantos = ParticipanteCurso::where('id_curso', '=', $actividad->id)->where('seccion', '=', $ultimo->seccion)->count();
                        if ($cuantos < $max_secc) {
                            $seccion = $ultimo->seccion;
                        } else {
                            for ($i = 0; $i < count($secc); $i++) {
                                if ($secc[$i] == $ultimo->seccion) {
                                    $seccion = $secc[$i + 1];
                                }
                            }
                        }
                    }else{
                        $seccion = '1';
                    }
                }elseif($usuario->tipo == 'Webinar'){
                    $max_secc = $actividad->max;
                    $cuantos = ParticipanteWebinar::where('id_webinar', '=', $actividad->id)->count();
                    if ($cuantos > $max_secc) {
                        Session::set('error', 'No hay cupos disponibles en el webinar '.$actividad->nombre);
                        return view('inscripciones.inscripciones', $data);
                    }
            }

                if($existe->count()){           //Caso en que el usuario ya se encuentre registrado.
                    if($usuario->tipo == 'Diplomado' || $usuario->tipo == 'Cápsula'){
                        $participante = Participante::where('id_usuario', '=', $existe[0]->id)->get();
                        $registrado = ParticipanteCurso::where('id_participante','=', $participante[0]->id)->where('id_curso', '=', $actividad->id)->get();
                        if($registrado->count()) {
                            Session::set('error', 'El usuario ya se encuentra inscrito en la actividad');
                            return view('inscripciones.inscripciones', $data);

                        }else{
                            $participante_nuevo = new ParticipanteCurso();
                            $participante_nuevo->id_participante = $participante[0]->id;
                            $participante_nuevo->id_curso = $actividad->id;
                            $participante_nuevo->seccion = $seccion;
                            $participante_nuevo->save();
                        }

                    }elseif($usuario->tipo == 'Webinar'){
                        $participante = Participante::where('id_usuario', '=', $existe[0]->id)->get();
                        $registrado = ParticipanteWebinar::where('id_participante','=', $participante[0]->id)->where('id_webinar', '=', $actividad->id)->get();
                        if($registrado->count()) {
                            Session::set('error', 'El usuario ya se encuentra inscrito en la actividad');
                            return view('inscripciones.inscripciones', $data);
                        }
                        else {
                            $participante_nuevo = new ParticipanteWebinar();
                            $participante_nuevo->id_participante = $participante[0]->id;
                            $participante_nuevo->id_webinar = $actividad->id;
                            $participante_nuevo->save();
                        }
                    }

                    if($participante_nuevo->save()) {
                        if($usuario->tipo == 'Diplomado' || $usuario->tipo == 'Cápsula'){ // Registro de pago
                            $participante = Participante::where('id_usuario', '=', $existe[0]->id)->get();
                            $pago = new Pago();
                            $pago->id_participante = $participante[0]->id;
                            $pago->id_curso = $actividad->id;
                            $pago->monto = $usuario->monto;
                            $pago->id_modalidad_pago = $usuario->id_modalidad_pago;
                            $pago->aprobado = true;
                            $pago->numero_pago = $usuario->numero_pago;
                            $pago->id_banco = $usuario->id_banco;
                            $pago->save();
                        }

//                        Mail::send('emails.inscripcion2', $data, function ($message) use ($data) {
//                            $message->subject('CIETE - Inscripción')
//                                ->to($data['email'], 'CIETE')
//                                ->replyTo($data['email']);
//                        });


                        DB::table('preinscripciones')->where('id', '=', $id)->delete();
                        $data['usuarios'] = Preinscripcion::all();
                        foreach ($data['usuarios'] as $usr) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                            if($usr->tipo != 'Webinar') {
                                $curso = Curso::where('id', '=', $usr->id_curso)->get();
                            }else{
                                $curso = Webinar::where('id', '=', $usr->id_curso)->get();
                            }
                            $modo = ModalidadPago::where('id', '=', $usr->id_modalidad_pago)->get();
                            $usr['curso_nombre'] = $curso[0]->nombre;
                            $usr['modalidad'] = $modo[0]->nombre;
                            $usr['banco'] = Banco::find($usr->id_banco);

                        }
                        $data['tipos'] = ['Cápsula', 'Diplomado', 'Webinar'];
                        Session::set('mensaje', 'El usuario fue inscrito con éxito.');
                        return view('inscripciones.inscripciones', $data);
                    }else{
                        Session::set('error', 'Ha ocurrido un error inesperado');
                        return view('inscripciones.inscripciones', $data);
                    }

                }else{      // Caso en que se registre un nuevo usuario
                    $user = new User();
                    $user->nombre = $usuario->nombre;
                    $user->apellido = $usuario->apellido;
                    $user->email = $usuario->email;
                    $user->foto = '';
                    $user->password = bcrypt($clave);
                    $user->save();

                    DB::table('role_user')->insert([
                        'user_id' => $user->id,
                        'role_id' => '3',
                    ]);

                    if($user->save()) {
                        $participante = new Participante();
                        $participante->id_usuario = $user->id;
                        $participante->nombre = $usuario->nombre;
                        $participante->apellido = $usuario->apellido;
                        $participante->documento_identidad = $usuario->di;
                        $participante->di_file = '';
                        $participante->telefono = '';
                        $participante->direccion = '';
                        $participante->celular = '';
                        $participante->correo_alternativo = '';
                        $participante->twitter = '';
                        $participante->ocupacion = '';
                        $participante->titulo_pregrado = '';
                        $participante->universidad = '';
                        $participante->nuevo = true;
                        $participante->save();

                        if($participante->save()) {
                            if ($usuario->tipo == 'Diplomado' || $usuario->tipo == 'Cápsula') {
                                $participante = Participante::find($participante->id);
                                $participante_nuevo = new ParticipanteCurso();
                                $participante_nuevo->id_participante = $participante->id;
                                $participante_nuevo->id_curso = $actividad->id;
                                $participante_nuevo->seccion = $seccion;
                                $participante_nuevo->save();

                            } elseif ($usuario->tipo == 'Webinar') {
                                $participante = Participante::find($participante->id);
                                $participante_nuevo = new ParticipanteWebinar();
                                $participante_nuevo->id_participante = $participante->id;
                                $participante_nuevo->id_webinar = $actividad->id;
                                $participante_nuevo->save();
                            }
                            if($participante_nuevo->save()) {
                                if($usuario->tipo == 'Diplomado' || $usuario->tipo == 'Cápsula'){ // Registro de pago
                                    $pago = new Pago();
                                    $pago->id_participante = $participante->id;
                                    $pago->id_curso = $actividad->id;
                                    $pago->monto = $usuario->monto;
                                    $pago->id_modalidad_pago = $usuario->id_modalidad_pago;
                                    $pago->aprobado = true;
                                    $pago->numero_pago = $usuario->numero_pago;
                                    $pago->id_banco = $usuario->id_banco;
                                    $pago->save();
                                }

//                                Mail::send('emails.inscripcion', $data, function ($message) use ($data) {
//                                    $message->subject('CIETE - Inscripción')
//                                        ->to($data['email'], 'CIETE')
//                                        ->replyTo($data['email']);
//                                });

                                DB::table('preinscripciones')->where('id', '=', $id)->delete();
                                $data['usuarios'] = Preinscripcion::all();
                                foreach ($data['usuarios'] as $usr) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                                    if($usr->tipo != 'Webinar') {
                                        $curso = Curso::where('id', '=', $usr->id_curso)->get();
                                    }else{
                                        $curso = Webinar::where('id', '=', $usr->id_curso)->get();
                                    }
                                    $modo = ModalidadPago::where('id', '=', $usr->id_modalidad_pago)->get();
                                    $usr['curso_nombre'] = $curso[0]->nombre;
                                    $usr['modalidad'] = $modo[0]->nombre;
                                    $usr['banco'] = Banco::find($usr->id_banco);
                                }
                                $data['tipos'] = ['Cápsula', 'Diplomado', 'Webinar'];
                                Session::set('mensaje', 'El usuario fue inscrito con éxito.');
                                return view('inscripciones.inscripciones', $data);
                            }else{
                                Session::set('error', 'Ha ocurrido un error inesperado');
                                return view('inscripciones.inscripciones', $data);
                            }
                        }else{
                            Session::set('error', 'Ha ocurrido un error inesperado');
                            return view('inscripciones.inscripciones', $data);
                        }
                    }else{
                        Session::set('error', 'Ha ocurrido un error inesperado');
                        return view('inscripciones.inscripciones', $data);
                    }
                }

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}


	public function verDocumentos($id) {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('activar_inscripcion')) {    // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['busq_'] = false;
                $usuario = Preinscripcion::find($id);
                $data['docs'] = '';
                $data['nombres'] = ['Documento de Identidad', 'Titulo', 'Recibo'];
                if($usuario->documento_identidad != ''){
                    $data['docs'][0] = $usuario->documento_identidad;
                }else{
                    $data['docs'][0] = '';
                }
                if($usuario->titulo != ''){
                    $data['docs'][1] = $usuario->titulo;
                }else{
                    $data['docs'][1] = '';
                }
                if($usuario->recibo != ''){
                    $data['docs'][2] = $usuario->recibo;
                }else{
                    $data['docs'][2] = '';
                }

                return view('inscripciones.documentos', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
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

            if ($usuario_actual->can('activar_inscripcion')) {
                $data['errores'] = '';
                $data['busq_'] = false;
                $path = public_path() . '/documentos/preinscripciones_pdf/' . $doc;

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


	public function destroy($id) {
        try {
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if ($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            } else {
                $data['foto'] = 'foto_participante.png';
            }

            if ($usuario_actual->can('desactivar_inscripcion')) {
                $data['errores'] = '';
                $data['busq_'] = false;
                $usuario = Preinscripcion::find($id);
                $data['motivo'] = Input::get('motivo');
                if($data['motivo'] == null){
                    $data['usuarios'] = Preinscripcion::all();
                    $data['tipos'] = ['Cápsula', 'Diplomado', 'Webinar'];
                    Session::set('error', 'El motivo no puede estar vacío');
                    return view('inscripciones.inscripciones', $data);
                }

                $data['nombre'] = $usuario->nombre;
                $data['apellido'] = $usuario->apellido;
                $data['email'] = $usuario->email;
                $data['actividad'] = '';

                if($usuario->tipo == 'Diploamdo' || $usuario->tipo == 'Cápsula'){
                    $actividad = Curso::find($usuario->id_curso);
                    $data['actividad'] = $actividad->nombre;
                    $data['tipo'] = 'curso';
                }elseif($usuario->tipo == 'Webinar'){
                    $actividad = Webinar::find($usuario->id_curso);
                    $data['actividad'] = $actividad->nombre;
                    $data['tipo'] = 'webinar';
                }

                DB::table('preinscripciones')->where('id', '=', $id)->delete();
                $data['usuarios'] = Preinscripcion::all();
                foreach ($data['usuarios'] as $usuario) {   // Se asocia el tipo a cada curso (Cápsulo o Diplomado)
                    if($usuario->tipo != 'Webinar') {
                        $curso = Curso::where('id', '=', $usuario->id_curso)->get();
                    }else{
                        $curso = Webinar::where('id', '=', $usuario->id_curso)->get();
                    }
                    $modo = ModalidadPago::where('id', '=', $usuario->id_modalidad_pago)->get();
                    $usuario['curso_nombre'] = $curso[0]->nombre;
                    $usuario['modalidad'] = $modo[0]->nombre;
                    $usuario['banco'] = Banco::find($usuario->id_banco);

                }
//                Mail::send('emails.rechazo', $data, function ($message) use ($data) {
//                    $message->subject('CIETE - Inscripción rechazada')
//                        ->to($data['email'], 'CIETE')
//                        ->replyTo($data['email']);
//                });
                $data['tipos'] = ['Diplomado', 'Cápsula', 'Webinar'];
                Session::set('mensaje', 'El usuario fue rechazado con éxito.');
                return view('inscripciones.inscripciones', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
                return view('errors.sin_permiso');
            }
        }catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}

}
