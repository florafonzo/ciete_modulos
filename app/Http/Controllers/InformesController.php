<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Nota;
use App\Models\Participante;
use App\Models\ParticipanteCurso;
use App\Models\Profesor;
use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Exception;
use DB;
use DateTime;

use Barryvdh\DomPDF\Facade as PDF;

use App\Models\Informe;
use Illuminate\Http\Request;

class InformesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
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
                $data['busq_'] = false;
                $data['busq'] = false;
                $data['informes'] = Informe::all();

                foreach ($data['informes'] as $informe) {
                    $informe['profesor'] = Profesor::find($informe->id_profesor);
                    $informe['curso'] = Curso::find($informe->id_curso);
                    $informe['modulo'] = Modulo::find($informe->id_modulo);
                    $informe['nombre'] = $informe['profesor']->nombre;
                    $informe['apellido'] = $informe['profesor']->apellido;
                    $informe['di'] = $informe['profesor']->documento_identidad;
                    $informe['email'] = $informe['profesor']->email;
                    $informe['celular'] = $informe['profesor']->celular;
                }

                return view('informes.informes', $data);

            }else{      // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        }
            catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}


	public function verInforme($id_informe, $id_profesor, $id_curso, $id_modulo, $seccion) {
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
                $data['busq_'] = false;
                $data['busq'] = false;
                $seccion = str_replace(' ', '', $seccion);
                $informe = Informe::find($id_informe);
                $data['curso'] = Curso::find($id_curso);
                $data['inicio'] = new DateTime($data['curso']->fecha_inicio);
                $data['fin'] = new DateTime($data['curso']->fecha_fin);
                $data['modulo'] = Modulo::find($id_modulo);
                $profesor = Profesor::find($id_profesor);
                $usuario = User::find($profesor->id_usuario);
                if($data['curso']->id_tipo == '1'){
                    $data['cohorte'] = $data['curso']->cohorte;
                }else{
                    $data['cohorte'] = '';
                }
                $data['grupo'] = $seccion;
                $data['conclusion'] = $informe->conclusion;
                $data['positivo'] = $informe->aspectos_positivos;
                $data['negativo'] = $informe->aspectos_negativos;
                $data['sugerencias'] = $informe->sugerencias;
                $data['participantes'] = '';
                $data['nombre'] = $profesor->nombre;
                $data['apellido'] = $profesor->apellido;
                $data['correo'] = $usuario->email;
                $data['ci'] = $profesor->documento_identidad;
                $data['celular'] = $profesor->celular;
                $data['fecha_descarga'] = $informe->fecha_descarga;

                $data['ausentes'] = 0;
                $data['aprobados'] = 0;
                $data['reprobados'] = 0;
                $data['desertores'] = 0;
                $participantes = ParticipanteCurso::where('id_curso', '=', $id_curso)->where('seccion', '=', $seccion)->select('id_participante')->get();
                $data['total'] = $participantes->count();
                if($participantes->count()) {
                    foreach ($participantes as $index => $part) {
                        $alumno = Participante::where('id', '=', $part->id_participante)->get();
                        $notas = Nota::where('id_participante_curso', '=', $part->id)
                            ->where('id_modulo', '=', $id_modulo)->get();
                        if($notas->count()) {
                            $final = 0;
                            $porcentaje = 0;
                            foreach ($notas as $nota) {
                                $calif = $nota->calificacion;
                                $porcent = $nota->porcentaje;
                                $porcentaje = ($porcentaje + $porcent);
                                $final = $final + ($calif * ($porcent / 100));
                            }

                            $proyecto = '';
                            if($notas->count() == 1){
                                $proyecto = 'D';
                                $data['desertores'] = $data['desertores'] + 1;
                            }elseif($notas->count() > 1 && $final < 10) {
                                $proyecto = 'R';
                                $data['reprobados'] = $data['reprobados'] + 1;
                            }
                            if ($final >= 10) {
                                $proyecto = 'A';
                                $data['aprobados'] = $data['aprobados'] + 1;
                            }

                            $data['participantes'][$index] = [[$alumno[0]->nombre], [$alumno[0]->apellido], [$final], [$proyecto]];
                        }else{
                            $data['participantes'][$index] = [[$alumno[0]->nombre], [$alumno[0]->apellido], [0], ['AU']];
                            $data['ausentes'] = $data['ausentes'] + 1;
                        }
                    }
                }else{
                    $data['participantes'] = '';
                }

                if($data['participantes'] != '') {
                    usort($data['participantes'], array($this, "querySort")); //Ordenar por orden alfabetico segun el apellido
                }

//                dd($data['participantes']);

                if($data['participantes'] != ''){
                    $pdf = PDF::loadView('informes.vista-informe',$data);
                    return $pdf->stream($data['modulo']->nombre."-".$data['cohorte']."-".$data['grupo']."-".date("Y").".pdf", array('Attachment'=>0));
                }else{
                    Session::set('error', 'Disculpe, no existen participantes en el modulo '.$data['modulo']->nombre);
                    return $this->index();
                }

            }else{      // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error

                return view('errors.sin_permiso');
            }

        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
	}

    function querySort ($x, $y) {
        return strcasecmp($x[1][0], $y[1][0]);
    }

    public function buscarInforme() {
        try{
            //Verificación de los permisos del usuario para poder realizar esta acción
            $usuario_actual = Auth::user();
            if($usuario_actual->foto != null) {
                $data['foto'] = $usuario_actual->foto;
            }else{
                $data['foto'] = 'foto_participante.png';
            }
            if($usuario_actual->can('ver_informes_academicos')) {   // Si el usuario posee los permisos necesarios continua con la acción
                $data['errores'] = '';
                $data['busq_'] = true;
                $data['busq'] = true;
                $val = '';
                $param = Input::get('parametro');
                if($param == '0'){
                    Session::set('error', 'Debe seleccionar el parametro por el cual desea buscar');
                    return $this->index();
                }

                if (empty(Input::get('busqueda'))) {
                    Session::set('error', 'Coloque el elemento que desea buscar');
                    return $this->index();
                }else{
                    $busq = Input::get('busqueda');
                }

                if($param == 'curso'){
                    $cursos = Curso::where('nombre', 'ilike', '%'.$busq.'%')->get();
                    if($cursos->count()) {
                        $data['informes'] = [];
                        foreach ($cursos as $index => $curso) {
                            $existe = Informe::where('id_curso', '=', $curso->id)->get();
                            if($existe->count()) {
//                                $pos = $data['informes']->count();
                                $data['informes'][$index] = $existe[0];
                            }else{
                                $data['informes'][$index] = [];
                            }
                        }
                        $data['informes'] = array_filter($data['informes']);
                    }else{
                        $data['informes'] = [];
                    }
                }elseif($param == 'modulo'){
                    $modulos = Modulo::where('nombre', 'ilike', '%'.$busq.'%')->get();
                    if($modulos->count()) {
                        $data['informes'] = [];
                        foreach ($modulos as $index => $modulo) {
                            $existe = Informe::where('id_modulo', '=', $modulo->id)->get();
                            if($existe->count()) {
//                                $pos = $data['informes']->count();
                                $data['informes'][$index] = $existe[0];
                            }else{
                                $data['informes'][$index] = [];
                            }
                        }
                        $data['informes'] = array_filter($data['informes']);
                    }else{
                        $data['informes'] = [];
                    }
                }elseif($param == 'seccion'){
                    $busq = str_replace(' ', '', $busq);
                    $existe = Informe::where('seccion', 'ilike', '%'.$busq.'%')->get();
                    if($existe->count()) {
                        $data['informes'] = $existe;
                    }else{
                        $data['informes'] = [];
                    }
                }

//                dd($data['informes']);
                if($data['informes'] != []) {
                    foreach ($data['informes'] as $informe) {
                        $informe['profesor'] = Profesor::find($informe->id_profesor);
                        $informe['curso'] = Curso::find($informe->id_curso);
                        $informe['modulo'] = Modulo::find($informe->id_modulo);
                        $informe['nombre'] = $informe['profesor']->nombre;
                        $informe['apellido'] = $informe['profesor']->apellido;
                        $informe['di'] = $informe['profesor']->documento_identidad;
                        $informe['email'] = $informe['profesor']->email;
                        $informe['celular'] = $informe['profesor']->celular;
                    }
                }

                return view('informes.informes', $data);

            }else{ // Si el usuario no posee los permisos necesarios se le mostrará un mensaje de error
                return view('errors.sin_permiso');
            }
        }
        catch (Exception $e) {

            return view('errors.error')->with('error',$e->getMessage());
        }
    }
}
