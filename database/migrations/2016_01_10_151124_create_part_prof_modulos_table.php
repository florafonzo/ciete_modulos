<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartProfModulosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('part_prof_modulos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_participante')->unsigned();
			$table->integer('id_profesor')->unsigned();
			$table->integer('id_modulo')->unsigned();
			$table->string('evaluacion');
			$table->integer('calificacion');
			$table->integer('porcentaje');

			$table->timestamps();


			$table->foreign('id_participante')->references('id')->on('participantes')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('id_profesor')->references('id')->on('profesores')
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
		Schema::drop('part_prof_modulos');
	}

}
