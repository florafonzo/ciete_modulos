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
            'nombre' => 'required|max:255',
            'apellido' => 'required|max:255',
            'di' => 'required|max:100|min:2',
            'email' => 'required|email|max:255',
            'tipo_pago' => 'required',
            'banco' => 'required',
            'monto' => 'required|integer',
            'numero_pago' => 'required',
        ];
    }

}

