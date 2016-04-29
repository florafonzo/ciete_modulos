<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Curso;
use App\Models\ModalidadPago;
use App\Models\Pago;
use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

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
				$data['pagos'] = Pago::where('aprobado', '=', false)->get();
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
                foreach ($data['pagos'] as $pago) {
                    $pago['participante'] = Participante::find($pago->id_participante);
                    $pago['curso'] = Curso::find($pago->id_curso);
                    $pago['modalidad'] = ModalidadPago::find($pago->id_modalidad_pago);
                }
                $data['tipos'] = ['Cápsula', 'Diplomado'];
                $param = Input::get('parametro');
                if($param == '0'){
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return view('pagos.pagos', $data);
                }
                if ($param != 'tipo'){
                    if (empty(Input::get('busqueda'))) {
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
                    if($participantes->count()) {
                        foreach ($participantes as $index => $part) {
                            $pago = Pago::where('id_participante', '=', $part->id)->get();
                            $data['pagos'][$index] = $pago[0];
                        }
                    }else{
                        $data['pagos'] = '';
                    }
                }elseif($param == 'curso'){
                    $cursos = Curso::where('nombre', 'ilike', '%' . $busq . '%')
                                    ->orderBy('created_at')->get();
                    if($cursos->count()) {
                        foreach ($cursos as $index => $curso) {
                            $pago = Pago::where('id_curso', '=', $curso->id)->get();
                            $data['pagos'][$index] = $pago[0];
                        }
                    }else{
                        $data['pagos'] = '';
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

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
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
