<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsDestinatariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails_destinatarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('email_id');
            $table->string('displayTo', 255)->nullable();
            $table->string('addressTo', 255);
            $table->tinyInteger('type')->default(1)->comment('1 = to, 2 = cc, 3 = bcc');
            $table->foreign('email_id')->references('id')->on('emails');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('emails_destinatarios');
    }
}
