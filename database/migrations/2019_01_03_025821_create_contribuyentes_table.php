<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatecontribuyentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contribuyentes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rut', 10);
            $table->string('razonSocial', 255);
            $table->integer('numeroResolucion');
            $table->string('fechaResolucion', 11);
            $table->string('mail', 100);
            $table->string('url', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('rut');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contribuyentes');
    }
}
