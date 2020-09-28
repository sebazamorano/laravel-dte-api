<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvioDteFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('envio_dte_file', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('envio_dte_id')->unsigned();
            $table->bigInteger('file_id')->unsigned();
            $table->timestamps();
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
        Schema::dropIfExists('envio_dte_file');
    }
}
