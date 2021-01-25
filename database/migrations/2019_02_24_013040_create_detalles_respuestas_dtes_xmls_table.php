<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallesRespuestasDtesXmlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_respuestas_dtes_xmls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('respuesta_dte_xml_id');
            $table->tinyInteger('tipoDte')->nullable();
            $table->unsignedInteger('folio');
            $table->date('fchEmis')->nullable();
            $table->string('rutEmisor', 10)->nullable();
            $table->string('rutRecep', 10)->nullable();
            $table->decimal('mntTotal', 18, 0)->nullable();
            $table->tinyInteger('estado')->nullable();
            $table->string('glosa', 255)->nullable();
            $table->string('digestValue', 64)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('respuesta_dte_xml_id')->references('id')->on('respuestas_dtes_xmls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('detalles_respuestas_dtes_xmls');
    }
}
