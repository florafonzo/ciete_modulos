<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\ContactoRequest;
use Validator;
use Input;
use Mail;
use Response;

class InformacionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function mision_vision()
	{
		return view('informacion.mision_vision');
	}

    public function estructura()
	{
		return view('informacion.estructura');
	}

    public function servicios()
	{
		return view('informacion.servicios');
	}

    public function equipo()
	{
		return view('informacion.equipo');
	}

    public function getcontacto()
	{
		$show = 'false';
		return \View::make('informacion.contacto', compact('show'));
	}

    public function creditos()
    {
        return view('informacion.creditos');
    }

    public function postContacto(ContactoRequest $request)
    {
    	$show = 'true';

    	$data = $request->only('nombre', 'apellido', 'lugar', 'correo' , 'comentario');

    	$data['messageLines'] = explode("\n", $request->get('comentario'));

	    Mail::send('emails.contacto', $data, function ($message) use ($data) {
	      $message->subject('Contacto:'.$data['nombre'])
	      		  ->to('app@cieteula.org', 'CIETE')
	              ->replyTo($data['correo']);
	    });

	    return \View::make('informacion.contacto', compact('show'));

    	//return back()->withSuccess("Gracias por tu mensaje. Ha sido enviado");
  	}

    public function ayuda() {
        return view('informacion.ayuda');
    }

	public function tutorial() {
        return view('informacion.tutorial');
    }

    public function descargarAyuda(){
        $path = public_path() . '/documentos/ayuda.pdf';

        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; ayuda.pdf',
        ]);
    }
}
