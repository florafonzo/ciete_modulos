<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Response;

use App\Models\Preinscripcion;
use Illuminate\Http\Request;

class InscripcionController extends Controller {


	public function indexCurso() {
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
	public function verPdf($id)
	{
        $usuario = Preinscripcion::find($id);
        $filename = $usuario->documento_identidad;
        $path = public_path().'/documentos/preinscripciones_pdf/'.$filename;
//        dd($path);

        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; '.$filename,
        ]);
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
