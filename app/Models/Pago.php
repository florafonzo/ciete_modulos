<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pagos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_participante','id_curso','por_partes', 'monto', 'banco'];

}
