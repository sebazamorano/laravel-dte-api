<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstadisticasEnviosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estadisticas_envios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('envio_dte_id');
            $table->string('tipoDoc', 3)->nullable();
            $table->integer('informado')->unsigned()->nullable();
            $table->integer('acepta')->unsigned()->nullable();
            $table->integer('rechazo')->unsigned()->nullable();
            $table->integer('reparo')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('envio_dte_id')->references('id')->on('envios_dtes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('estadisticas_envios');
    }
}
