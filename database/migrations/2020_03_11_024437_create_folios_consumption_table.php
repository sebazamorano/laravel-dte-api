<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoliosConsumptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sii_folios_consumption', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->string('rutEmisor', 10);
            $table->string('rutEnvia', 10);
            $table->date('fchResol');
            $table->integer('nroResol');
            $table->date('fchInicio');
            $table->date('fchFinal');
            $table->integer('correlativo')->nullable()->default(1);
            $table->integer('secEnvio')->nullable();
            $table->dateTime('tmstFirmaEnv')->nullable();
            $table->string('dcf_id')->nullable();
            $table->dateTime('upload_datetime')->nullable();
            $table->integer('status')->nullable();
            $table->string('trackid',20)->nullable();
            $table->string('rspUpload', 100)->nullable();
            $table->string('glosa', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sii_folios_consumption');
    }
}
