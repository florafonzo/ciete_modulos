<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInformesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('informes', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('id_modulo')->unsigned();
			$table->date('fecha_descarga');
			$table->string('conclusion');
			$table->string('aspectos_positivos');
			$table->string('aspectos_negativos');
			$table->string('sugerencias');
			$table->timestamps();


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
		Schema::drop('informes');
	}

}
