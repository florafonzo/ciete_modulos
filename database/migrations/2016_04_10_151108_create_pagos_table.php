<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pagos', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('id_participante')->unsigned();
			$table->integer('id_curso')->unsigned();
			$table->integer('id_modalidad_pago')->unsigned();
			$table->integer('id_banco')->unsigned();
			$table->string('numero_pago');
			$table->boolean('aprobado');
			$table->string('monto');
			$table->timestamps();

			$table->foreign('id_participante')->references('id')->on('participantes')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('id_curso')->references('id')->on('cursos')
				->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_modalidad_pago')->references('id')->on('modalidad_pagos')
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
		Schema::drop('pagos');
	}

}
