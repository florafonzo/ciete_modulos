<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreinscripcionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('preinscripciones', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_curso')->unsigned();
			$table->integer('id_banco')->unsigned();
			$table->string('nombre');
			$table->string('apellido');
			$table->string('email');
			$table->string('documento_identidad');
			$table->string('titulo');
			$table->string('recibo');
			$table->string('tipo');
			$table->timestamps();


			$table->foreign('id_curso')->references('id')->on('cursos')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('id_banco')->references('id')->on('bancos')
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
		Schema::drop('preinscripciones');
	}

}
