<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRespuestasDtesXmlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuestas_dtes_xmls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('envio_dte_id');
            $table->tinyInteger('tipoRespuesta')->nullable();
            $table->string('rutResponde', 10)->nullable();
            $table->string('rutRecibe', 10)->nullable();
            $table->string('nmbContacto', 40)->nullable();
            $table->string('fonoContacto', 40)->nullable();
            $table->string('mailContacto', 80)->nullable();
            $table->integer('nroDetalles')->nullable();
            $table->datetime('tmstFirmaResp')->nullable();
            $table->unsignedBigInteger('file_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('envio_dte_id')->references('id')->on('envios_dtes');
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
        Schema::drop('respuestas_dtes_xmls');
    }
}
