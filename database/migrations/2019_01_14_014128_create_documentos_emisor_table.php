<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosEmisorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_emisor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('documento_id');
            $table->string('RUTEmisor', 10);
            $table->string('RznSoc', 100);
            $table->string('GiroEmis', 80);
            $table->string('Telefono1', 20)->nullable();
            $table->string('Telefono2', 20)->nullable();
            $table->string('CorreoEmisor', 80)->nullable();
            $table->string('Sucursal', 20)->nullable();
            $table->integer('CdgSIISucur')->nullable();
            $table->string('CodAdicSucur', 20)->nullable();
            $table->string('DirOrigen', 70);
            $table->string('CmnaOrigen', 20);
            $table->string('CiudadOrigen', 20)->nullable();
            $table->string('CdgVendedor', 60)->nullable();
            $table->string('IdAdicEmisor', 20)->nullable();
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
        Schema::drop('documentos_emisor');
    }
}
