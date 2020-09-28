<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosReceptorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_receptor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('documento_id');
            $table->string('RUTRecep', 10);
            $table->string('CdgIntRecep', 20)->nullable();
            $table->string('RznSocRecep', 100);
            $table->string('NumId', 20)->nullable();
            $table->string('Nacionalidad', 3)->nullable();
            $table->integer('TipoDocID')->nullable();
            $table->string('IdAdicRecep', 20)->nullable();
            $table->string('GiroRecep', 40);
            $table->string('Contacto', 80)->nullable();
            $table->string('CorreoRecep', 80)->nullable();
            $table->string('DirRecep', 70);
            $table->string('CmnaRecep', 20);
            $table->string('CiudadRecep', 20)->nullable();
            $table->string('DirPostal', 70)->nullable();
            $table->string('CmnaPostal', 20)->nullable();
            $table->string('CiudadPostal', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('documento_id')->references('id')->on('documentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('documentos_receptor');
    }
}
