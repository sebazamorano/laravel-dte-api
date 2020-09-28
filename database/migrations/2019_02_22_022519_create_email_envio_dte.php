<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailEnvioDte extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_envio_dte', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('email_id');
            $table->unsignedBigInteger('envio_dte_id');
            $table->foreign('email_id')->references('id')->on('emails');
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
        Schema::dropIfExists('email_envio_dte');
    }
}
