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
			$table->integer('id_part_prof_modulos')->unsigned();
			$table->string('evaluacion');
			$table->integer('calificacion');
			$table->integer('porcentaje');
			$table->timestamps();

			$table->foreign('id_part_prof_modulos')->references('id')->on('part_prof_modulos')
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
