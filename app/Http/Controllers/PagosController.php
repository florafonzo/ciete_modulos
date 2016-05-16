<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Curso;
use App\Models\ModalidadPago;
use App\Models\Pago;
use App\Models\Participante;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Mail;

class PagosController extends Controller {


	public function index() {
		try{

			//Verificación de los permisos del usuario para poder realizar esta acción
			$usuario_actual = Auth::user();
			if($usuario_actual->foto != null) {
				$data['foto'] = $usuario_actual->foto;
			}else{
				$data['foto'] = 'foto_participante.png';
			}

			if($usuario_actual->can('aprobar_pago')) {    // Si el usuario posee los permisos necesarios continua con la acción
				$data['errores'] = '';
				$data['busq_'] = false;
				$data['busq'] = false;
				$data['pagos'] = Pago::where('aprobado', '=', false)->paginate(5);
				foreach ($data['pagos'] as $pago) {
					$pago['participante'] = Participante::find($pago->id_participante);
					$pago['curso'] = Curso::find($pago->id_curso);
                    $pago['modalidad'] = ModalidadPago::find($pago->id_modalidad_pago);
				}
                $data['tipos'] = ['Cápsula', 'Diplomado'];

				return view('pagos.pagos', $data);

			}else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

				return view('errors.sin_permiso');
			}
		}
		catch (Exception $e) {

			return view('errors.error')->with('error',$e->getMessage());
		}
	}

    public function buscarPago() {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_lista_cursos')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['busq_'] = true;
                $data['busq'] = true;
                $data['tipos'] = ['Cápsula', 'Diplomado'];
                $param = Input::get('parametro');
                if($param == '0'){
                    $data['pagos'] = Pago::where('aprobado', '=', false)->get();
                    foreach ($data['pagos'] as $pago) {
                        $pago['participante'] = Participante::find($pago->id_participante);
                        $pago['curso'] = Curso::find($pago->id_curso);
                        $pago['modalidad'] = ModalidadPago::find($pago->id_modalidad_pago);
                    }
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('pagos.pagos', $data);
                }
                if ($param != 'tipo'){
                    if (empty(Input::get('busqueda'))) {
                        $data['pagos'] = Pago::where('aprobado', '=', false)->get();
                        foreach ($data['pagos'] as $pago) {
                            $pago['participante'] = Participante::find($pago->id_participante);
                            $pago['curso'] = Curso::find($pago->id_curso);
                            $pago['modalidad'] = ModalidadPago::find($pago->id_modalidad_pago);
                        }
                        Session::set('error', 'Coloque el elemento que desea buscar');
                        return view('pagos.pagos', $data);
                    }else{
                        $busq = Input::get('busqueda');
                    }
                }else{
                    $busq = Input::get('busqu');
                }
                if($param != 'curso') {
                    $participantes = Participante::where($param, 'ilike', '%' . $busq . '%')
                                                    ->orderBy('created_at')->get();
                    $data['pagos'] = '';
                    if($participantes->count()) {
                        foreach ($participantes as $index => $part) {
                            $pago_ = Pago::where('id_participante', '=', $part->id)->where('aprobado', '=', false)->get();
                            if($pago_->count()) {
                                if($data['pagos'] == '') {
                                    $data['pagos'][0] = $pago_[0];
                                }else{
                                    $len = $data['pagos']->count();
                                    $data['pagos'][$len+1] = $pago_[0];
                                }
                            }
                        }
                    }else{
                        $data['pagos'] = '';
                    }
                }elseif($param == 'curso'){
                    $cursos = Curso::where('nombre', 'ilike', '%' . $busq . '%')
                                    ->orderBy('created_at')->get();
                    $data['pagos'] = '';
                    if($cursos->count()) {
                        foreach ($cursos as $index => $curso) {
                            $pago_ = Pago::where('id_curso', '=', $curso->id)
                                        ->where('aprobado', '=', false)->get();
                            if($pago_->count()) {
                                if($data['pagos'] == '') {
                                    $data['pagos'][0] = $pago_[0];
                                }else{
                                    $len = $data['pagos']->count();
                                    $data['pagos'][$len+1] = $pago_[0];
                                }
                            }
                        }
                    }else{
                        $data['pagos'] = '';
                    }
                }
                if($data['pagos'] != ''){
                    foreach ($data['pagos'] as $pago) {
                        $pago['participante'] = Participante::find($pago->id_participante);
                        $pago['curso'] = Curso::find($pago->id_curso);
                        $pago['modalidad'] = ModalidadPago::find($pago->id_modalidad_pago);
                    }
                }
                return view('pagos.pagos', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }


    public function aprobarPago() {
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('aprobar_pago')) {    // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['busq_'] = false;
                $data['busq'] = false;
                $data['tipos'] = ['Cápsula', 'Diplomado'];
                $id_pago = Input::get('val');
                $pago_ = Pago::find($id_pago);
                $pago_->aprobado = true;
                $pago_->save();

                if($pago_->save()){
                    $data['pagos'] = Pago::where('aprobado', '=', false)->get();
                    if($data['pagos']->count()) {
                        foreach ($data['pagos'] as $pago) {
                            $pago['participante'] = Participante::find($pago->id_participante);
                            $pago['curso'] = Curso::find($pago->id_curso);
                            $pago['modalidad'] = ModalidadPago::find($pago->id_modalidad_pago);
                        }
                    }else{
                        $data['pagos'] = [];
                    }

                    $participante = Participante::find($pago_->id_participante);
                    $usuario = User::find($participante->id_usuario);
                    $data['pago_'] = $pago_;
                    $data['participante'] = Participante::find($pago_->id_participante);
                    $data['curso'] = Curso::find($pago_->id_curso);
                    $data['email'] = $usuario->email;
                    Mail::send('emails.pago-aprobado', $data, function ($message) use ($data) {
                        $message->subject('CIETE - Pago aprobado')
                            ->to($data['email'], 'CIETE')
                            ->replyTo($data['email']);
                    });
                    Session::set('mensaje', 'Pago aprobado con éxito');
                    return view('pagos.pagos', $data);
                }else{
                    $data['pagos'] = Pago::where('aprobado', '=', false)->get();
                    if($data['pagos']->count()) {
                        foreach ($data['pagos'] as $pago) {
                            $pago['participante'] = Participante::find($pago->id_participante);
                            $pago['curso'] = Curso::find($pago->id_curso);
                            $pago['modalidad'] = ModalidadPago::find($pago->id_modalidad_pago);
                        }
                    }else{
                        $data['pagos'] = [];
                    }
                    Session::set('error', 'Ha ocurrido un error inesperado');
                    return view('pagos.pagos', $data);
                }

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

    public function destroy($id_pago) {
        try{

            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }

            if($usuario_actual->can('aprobar_pago')) {    // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['busq_'] = false;
                $data['busq'] = false;
                $data['tipos'] = ['Cápsula', 'Diplomado'];
                $pago_ = Pago::find($id_pago);
                $participante = Participante::find($pago_->id_participante);
                $usuario = User::find($participante->id_usuario);
                $data['motivo'] = Input::get('motivo');
                if($data['motivo'] == null){
                    Session::set('error', 'El motivo no puede estar vacío');
                    return $this->index();
                }

                $data['pago'] = $pago_;
                $data['participante'] = Participante::find($pago_->id_participante);
                $data['curso'] = Curso::find($pago_->id_curso);
                $data['email'] = $usuario->email;
                DB::table('pagos')->where('id', '=', $id_pago)->delete();
                Mail::send('emails.rechazo', $data, function ($message) use ($data) {
                    $message->subject('CIETE - Inscripción rechazada')
                        ->to($data['email'], 'CIETE')
                        ->replyTo($data['email']);
                });
                Session::set('mensaje', 'El pago fue rechazado con éxito.');
                return $this->index();

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }

}
