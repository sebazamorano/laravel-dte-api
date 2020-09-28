<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnviosDtesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('envios_dtes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->unsignedBigInteger('certificado_empresa_id')->nullable();
            $table->string('rutEmisor', 10);
            $table->string('rutEnvia', 10);
            $table->string('rutReceptor', 10);
            $table->string('trackid', 20)->nullable();
            $table->string('setDteId', 80)->nullable();
            $table->string('rspUpload', 100)->nullable();
            $table->string('glosa', 100)->nullable();
            $table->tinyInteger('estadoRecepEnv')->nullable();
            $table->string('recepEnvGlosa', 255)->nullable();
            $table->integer('nroDte')->nullable();
            $table->date('fchResol')->nullable();
            $table->integer('nroResol')->unsigned()->nullable();
            $table->datetime('tmstFirmaEnv')->nullable();
            $table->string('digest', 255)->nullable();
            $table->string('correoRespuesta', 80)->nullable();
            $table->tinyInteger('estado')->nullable();
            $table->tinyInteger('boleta')->default(0);
            $table->tinyInteger('contribuyente')->default(0);
            $table->tinyInteger('IO')->default(0);
            $table->dateTime('fchRecep')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('certificado_empresa_id')->references('id')->on('certificados_empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('envios_dtes');
    }
}
