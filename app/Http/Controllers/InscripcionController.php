<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Participante;
use App\Models\ParticipanteCurso;
use App\Models\ParticipanteWebinar;
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
				$data['tipos'] = ['curso', 'webinar'];

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
				$data['tipos'] = ['curso', 'webinar'];
				$data['usuarios'] = [];
				$data['errores'] = '';
				$data['busq_'] = true;
				$val = '';
				$param = Input::get('parametro');
				if($param == '0'){
					$data['usuarios'] = Preinscripcion::all();
					Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
					return view('inscripciones.inscripciones', $data);
				}
				if ($param != 'tipo'){
					if (empty(Input::get('busqueda'))) {
						$data['usuarios'] = Preinscripcion::all();
						Session::set('error', 'Coloque el elemento que desea buscar');
						return view('cursos.cursos', $data);
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
						$val = 'curso';
					}else{
						$val = 'webinar';
					}
					$data['usuarios'] = Preinscripcion::where('tipo', '=', $val)
						->orderBy('created_at')->get();
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
                $secc = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                $id = Input::get('val');
                $usuario = Preinscripcion::find($id);
                $existe = User::where('email', '=', $usuario->email)->get();// se verifica si el usuario ya está registrado

                if($usuario->tipo == 'curso'){
                    $actividad = Curso::find($usuario->id_curso);
                    $data['tipo'] = 'curso';
                }elseif($usuario->tipo == 'webinar'){
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

                $seccion = '';
                if($usuario->tipo == 'curso'){
                    $max_secc = $actividad->id_curso;
                    $todo = ParticipanteCurso::where('id_curso', '=', $actividad->id_curso)->orderBy('created_at')->get();
                    $todo = end($todo);
                    $ultimo = end($todo);
                    $cuantos = ParticipanteCurso::where('id_curso', '=', $actividad->id_curso)->where('seccion', '=', $ultimo->seccion)->count();
                    if($cuantos < $max_secc){
                        $seccion = $ultimo->seccion;
                    }else{
                        for($i = 0; $i < count($secc); $i++){
                            if($secc[$i] == $ultimo->seccion){
                                $seccion = $secc[$i + 1];
                            }
                        }
                    }
                }elseif($usuario->tipo == 'webinar'){
                    $max_secc = $actividad->id_curso;
                    $todo = ParticipanteCurso::where('id_curso', '=', $actividad->id_curso)->orderBy('created_at')->get();
                    $todo = end($todo);
                    $ultimo = end($todo);
                    $cuantos = ParticipanteWebinar::where('id_webinaro', '=', $actividad->id_curso)->where('seccion', '=', $ultimo->seccion)->count();
                    if($cuantos < $max_secc){
                        $seccion = $ultimo->seccion;
                    }else{
                        for($i = 0; $i < count($secc); $i++){
                            if($secc[$i] == $ultimo->seccion){
                                $seccion = $secc[$i + 1];
                            }
                        }
                    }
                }
                dd($seccion);

                if($existe->count()){           //Caso en que el usuario ya se encuentre registrado.
                    if($usuario->tipo == 'curso'){
                        $participante = Participante::where('id_usuario', '=', $existe[0]->id)->get();
                        $participante_nuevo = new ParticipanteCurso();
                        $participante_nuevo->id_participante = $participante[0]->id;
                        $participante_nuevo->id_curso = $actividad->id;
                        $participante_nuevo->seccion = $seccion;
                        $participante_nuevo->save();

                    }elseif($usuario->tipo == 'webinar'){
                        $participante = Participante::where('id_usuario', '=', $existe[0]->id)->get();
                        $participante_nuevo = new ParticipanteWebinar();
                        $participante_nuevo->id_participante = $participante[0]->id;
                        $participante_nuevo->id_webinar = $actividad->id;
                        $participante_nuevo->seccion = $seccion;
                        $participante_nuevo->save();
                    }

                    if($participante_nuevo->save()) {
                        Mail::send('emails.inscripcion2', $data, function ($message) use ($data) {
                            $message->subject('CIETE - Inscripción')
                                ->to($data['email'], 'CIETE')
                                ->replyTo($data['email']);
                        });

                        DB::table('preinscripciones')->where('id', '=', $id)->delete();
                        $data['usuarios'] = Preinscripcion::all();
                        $data['tipos'] = ['curso', 'webinar'];
                        Session::set('mensaje', 'El usuario fue inscrito con éxito.');
                        return view('inscripciones.inscripciones', $data);
                    }else{
                        $data['usuarios'] = Preinscripcion::all();
                        $data['tipos'] = ['curso', 'webinar'];
                        Session::set('error', 'Ha ocurrido un error inesperado');
                        return view('inscripciones.inscripciones', $data);
                    }

                }else{      // Caso en que se registre un nuevo usuario
                    $user = new User();
                    $user->nombre = $data['nombre'];
                    $user->apellido = $data['apellido'];
                    $user->email = $data['email'];
                    $user->foto = '';
                    $user->password = bcrypt($clave);
                    $user->save();

//                    dd($user->id);
                    DB::table('role_user')->insert(
                        [
                        'user_id' => $user->id,
                        'role_id' => '3',
                        ]
                    );

                    if($user->save()) {
                        $participante = new Participante();
                        $participante->id_usuario = $user->id;
                        $participante->nombre = $data['nombre'];
                        $participante->apellido = $data['apellido'];
                        $participante->documento_identidad = $usuario->di;
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
                            if ($usuario->tipo == 'curso') {
                                $participante = Participante::find($participante->id);
                                $participante_nuevo = new ParticipanteCurso();
                                $participante_nuevo->id_participante = $participante->id;
                                $participante_nuevo->id_curso = $actividad->id;
                                $participante_nuevo->seccion = $seccion;
                                $participante_nuevo->save();

                            } elseif ($usuario->tipo == 'webinar') {
                                $participante = Participante::find($participante->id);
                                $participante_nuevo = new ParticipanteWebinar();
                                $participante_nuevo->id_participante = $participante->id;
                                $participante_nuevo->id_webinar = $actividad->id;
                                $participante_nuevo->seccion = $seccion;
                                $participante_nuevo->save();
                            }
                            if($participante_nuevo->save()) {

                                Mail::send('emails.inscripcion', $data, function ($message) use ($data) {
                                    $message->subject('CIETE - Inscripción')
                                        ->to($data['email'], 'CIETE')
                                        ->replyTo($data['email']);
                                });

                                DB::table('preinscripciones')->where('id', '=', $id)->delete();
                                $data['usuarios'] = Preinscripcion::all();
                                $data['tipos'] = ['curso', 'webinar'];
                                Session::set('mensaje', 'El usuario fue inscrito con éxito.');
                                return view('inscripciones.inscripciones', $data);
                            }else{
                                $data['usuarios'] = Preinscripcion::all();
                                $data['tipos'] = ['curso', 'webinar'];
                                Session::set('error', 'Ha ocurrido un error inesperado');
                                return view('inscripciones.inscripciones', $data);
                            }
                        }else{
                            $data['usuarios'] = Preinscripcion::all();
                            $data['tipos'] = ['curso', 'webinar'];
                            Session::set('error', 'Ha ocurrido un error inesperado');
                            return view('inscripciones.inscripciones', $data);
                        }
                    }else{
                        $data['usuarios'] = Preinscripcion::all();
                        $data['tipos'] = ['curso', 'webinar'];
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
                $path = public_path() . '/documentos/preinscripciones_pdf/' . $doc;

                return Response::make(file_get_contents($path), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; ' . $doc,
                ]);
            }
        }catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
