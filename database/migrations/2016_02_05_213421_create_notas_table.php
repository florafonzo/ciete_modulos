<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_participante_curso')->unsigned();
			$table->integer('id_modulo')->unsigned();
			$table->string('evaluacion');
			$table->integer('calificacion');
			$table->integer('porcentaje');
			$table->timestamps();

			$table->foreign('id_participante_curso')->references('id')->on('participante_cursos')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('id_modulo')->references('id')->on('modulos')
				->onUpdate('cascade')->onDelete('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notas');
	}

}
