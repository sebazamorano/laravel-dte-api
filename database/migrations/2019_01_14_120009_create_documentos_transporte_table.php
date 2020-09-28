<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosTransporteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_transporte', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('documento_id');
            $table->string('Patente', 8)->nullable();
            $table->string('RUTTrans', 10)->nullable();
            $table->string('RUTChofer', 10)->nullable();
            $table->string('NombreChofer', 30)->nullable();
            $table->string('DirDest', 70)->nullable();
            $table->string('CmnaDest', 20)->nullable();
            $table->string('CiudadDest', 20)->nullable();
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
        Schema::drop('documentos_transporte');
    }
}
