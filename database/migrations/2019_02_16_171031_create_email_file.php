<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_file', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('email_id');
            $table->unsignedBigInteger('file_id');
            $table->foreign('email_id')->references('id')->on('emails');
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
        Schema::dropIfExists('email_file');
    }
}
