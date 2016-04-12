<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Providers;

class PreinscripcionRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return true;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'curso' => 'required',
            'nombre' => 'required|max:255|min:3',
            'apellido' => 'required|max:255|min:4',
            'cedula' => 'required|mimes:pdf',
            'titulo' => 'required|mimes:pdf',
            'recibo' => 'required|mimes:pdf',
            'email' => 'required|email|max:255'
        ];
    }

}

