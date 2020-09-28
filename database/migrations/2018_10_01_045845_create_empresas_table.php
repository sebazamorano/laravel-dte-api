<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rut', 10);
            $table->string('razonSocial', 150);
            $table->string('direccion', 70);
            $table->integer('region_id')->unsigned();
            $table->integer('provincia_id')->unsigned();
            $table->integer('comuna_id')->unsigned();
            $table->string('giro', 80);
            $table->string('contactoSii', 80)->nullable();
            $table->string('passwordContactoSii', 100)->nullable();
            $table->string('contactoEmpresas', 80)->nullable();
            $table->string('passwordContactoEmpresas', 100)->nullable();
            $table->string('servidorSmtp', 255)->nullable();
            $table->date('fechaResolucion')->nullable();
            $table->integer('numeroResolucion')->nullable();
            $table->date('fechaResolucionBoleta')->nullable();
            $table->integer('numeroResolucionBoleta')->nullable();
            $table->string('nombreLogotipo', 255)->nullable();
            $table->string('archivoLogotipo', 255)->nullable();
            $table->integer('esEmisor')->default(0);
            $table->integer('esReceptor')->default(0);
            $table->integer('reglasNegocio')->default(0);
            $table->timestamps();
            $table->softDeletes();
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
        Schema::drop('empresas');
    }
}
