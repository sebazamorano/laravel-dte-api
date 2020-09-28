<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('cloud_id', 255)->nullable();
            $table->string('displayFrom', 255)->nullable();
            $table->string('addressFrom', 255);
            $table->string('deliveredTo', 255)->nullable();
            $table->string('subject', 255);
            $table->text('texto')->nullable();
            $table->text('html')->nullable();
            $table->tinyInteger('IO')->default(0)->comment('Entrada/salida 0 = Out Salida. 1 = In entrada.');
            $table->tinyInteger('leido')->nullable()->comment('Solo se registra cuando es un correo de entrada');
            $table->tinyInteger('procesado')->nullable()->comment('Indica si el correo ya fue procesado');
            $table->tinyInteger('resaltado')->nullable()->comment('Indica si el correo esta resaltado');
            $table->tinyInteger('bandeja')->nullable()->comment('1 = Entrada, 2 = Enviados, 3 = SPAM, 4 = borradores, 5 = No deseados, 6 = Eliminados ');
            $table->dateTime('fecha');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('emails');
    }
}
