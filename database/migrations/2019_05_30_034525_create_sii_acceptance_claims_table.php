<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiiAcceptanceClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sii_acceptance_claims', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->string('rut');
            $table->integer('doc_type');
            $table->bigInteger('folio');
            $table->string('action');
            $table->integer('response_code')->nullable();
            $table->string('response_description')->nullable();
            $table->dateTime('event_date')->nullable();
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
        Schema::drop('sii_acceptance_claims');
    }
}
