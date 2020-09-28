<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadesEconomicasEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividades_economicas_empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('actividad_economica_id');
            $table->unsignedBigInteger('empresa_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('actividad_economica_id')->references('id')->on('actividades_economicas');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('actividades_economicas_empresas');
    }
}
