<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentoEnvioDte extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documento_envio_dte', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('documento_id');
            $table->unsignedBigInteger('envio_dte_id');
            $table->timestamps();
            $table->foreign('documento_id')->references('id')->on('documentos');
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
        Schema::dropIfExists('documento_envio_dte');
    }
}
