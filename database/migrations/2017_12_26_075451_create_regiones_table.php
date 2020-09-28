<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regiones', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('pais_id')->unsigned();
            $table->string('nombre', 60)->unique();
            $table->string('ISO_3166_2_CL', 6);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('pais_id')->references('id')->on('pais');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('regiones');
    }
}
