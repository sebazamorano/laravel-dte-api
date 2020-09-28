<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosActividadesEconomicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_actividades_economicas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('documento_id');
            $table->unsignedBigInteger('actividad_economica_id');
            $table->integer('acteco')->unsigned();
            $table->string('descripcion', 45)->nullable();
            $table->softDeletes();
            $table->foreign('documento_id')->references('id')->on('documentos');
            $table->foreign('actividad_economica_id')->references('id')->on('actividades_economicas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('documentos_actividades_economicas');
    }
}
