<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntidadesSucursalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entidades_sucursales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('entidad_id');
            $table->unsignedTinyInteger('tipo')->nullable();
            $table->string('nombre', 40);
            $table->string('direccion', 200);
            $table->string('direccion_xml', 60)->nullable();
            $table->integer('region_id')->unsigned()->nullable();
            $table->integer('provincia_id')->unsigned()->nullable();
            $table->integer('comuna_id')->unsigned()->nullable();
            $table->string('comuna', 20)->nullable();
            $table->string('ciudad', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('entidad_id')->references('id')->on('entidades');
            $table->foreign('region_id')->references('id')->on('regiones');
            $table->foreign('provincia_id')->references('id')->on('provincias');
            $table->foreign('comuna_id')->references('id')->on('comunas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entidades_sucursales');
    }
}
