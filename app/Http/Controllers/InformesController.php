<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Informe;
use Illuminate\Http\Request;

class InformesController extends Controller {

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

		if($usuario_actual->can('ver_informes_academicos')) { // Si el usuario posee los permisos necesarios continua con la acción
			$data['errores'] = '';
			$data['informes'] = Informe::all();
			return view('informes.informes', $data);

		}else{      // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

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
