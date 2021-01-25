<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvioDteRevisionDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('envio_dte_revision_detalles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('envio_dte_revision_id');
            $table->unsignedBigInteger('empresa_id');
            $table->text('detalle')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('envio_dte_revision_id')->references('id')->on('envio_dte_revisiones');
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
        Schema::drop('envio_dte_revision_detalles');
    }
}
