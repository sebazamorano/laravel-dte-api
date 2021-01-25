<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvioDteErroresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('envio_dte_errores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('envio_dte_id');
            $table->text('texto');
            $table->timestamps();
            $table->softDeletes();
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
        Schema::drop('envio_dte_errores');
    }
}
