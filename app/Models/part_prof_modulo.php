<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class part_prof_modulo extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'part_prof_modulos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_participante','id_profesor','id_modulo', 'seccion'];

}
