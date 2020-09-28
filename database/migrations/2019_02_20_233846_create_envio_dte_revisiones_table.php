<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvioDteRevisionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('envio_dte_revisiones', function (Blueprint $table) {
            $table->BigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('envio_dte_id');
            $table->unsignedInteger('folio')->nullable();
            $table->tinyInteger('tipoDte')->nullable();
            $table->string('estado', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('empresa_id')->references('id')->on('empresas');
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
        Schema::drop('envio_dte_revisiones');
    }
}
