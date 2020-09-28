<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificadosEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificados_empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('original');
            $table->unsignedBigInteger('pem')->nullable();
            $table->string('rut', 10);
            $table->string('password', 45);
            $table->datetime('fechaEmision')->nullable();
            $table->datetime('fechaVencimiento')->nullable();
            $table->json('subject')->nullable();
            $table->json('issuer')->nullable();
            $table->integer('enUso')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('original')->references('id')->on('files');
            $table->foreign('pem')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('certificados_empresas');
    }
}
