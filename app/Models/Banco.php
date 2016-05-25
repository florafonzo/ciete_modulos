<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model {

    protected $table = 'bancos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre'];



}
