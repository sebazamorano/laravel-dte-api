<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiiDocumentsInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sii_documents_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->string('rut');
            $table->integer('doc_type');
            $table->bigInteger('folio');
            $table->datetime('reception_date')->nullable()->default(null);
            $table->integer('transferable_response_code')->nullable()->default(null);
            $table->string('transferable_description', 70)->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('company_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sii_documents_information');
    }
}
