<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Informe extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'informes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_modulo','fecha_descarga', 'conclusion', 'aspectos_positivos', 'aspectos_negativos', 'sugerencias'];

}
