<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatecafsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cafs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('tipo_documento_id');
            $table->unsignedBigInteger('file_id');
            $table->unsignedInteger('folioDesde');
            $table->unsignedInteger('folioHasta');
            $table->unsignedInteger('folioUltimo');
            $table->date('fa');
            $table->date('fechaVencimiento');
            $table->integer('disponibles');
            $table->boolean('enUso');
            $table->boolean('completado');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('tipo_documento_id')->references('id')->on('tipos_documentos');
            $table->foreign('file_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cafs');
    }
}
