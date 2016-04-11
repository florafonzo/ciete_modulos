<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_part_prof_modulo','id_modulo','calificacion','evaluacion', 'porcentaje'];


}
