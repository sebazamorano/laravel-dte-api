<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_detalles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('documento_id');
            $table->integer('NroLinDet');
            $table->string('TpoCodigo', 10)->nullable();
            $table->string('VlrCodigo', 35)->nullable();
            $table->string('NmbItem', 80);
            $table->text('DscItem')->nullable();
            $table->decimal('QtyItem', 18, 6)->nullable();
            $table->string('UnmdItem', 4)->nullable();
            $table->decimal('PrcItem', 18, 6)->nullable();
            $table->decimal('DescuentoPct', 5, 2)->nullable();
            $table->bigInteger('DescuentoMonto')->nullable();
            $table->decimal('RecargoPct', 5, 2)->nullable();
            $table->bigInteger('RecargoMonto')->nullable();
            $table->tinyInteger('IndExe')->default(0);
            $table->integer('adicional')->nullable();
            $table->bigInteger('MontoItem');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('documento_id')->references('id')->on('documentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('documentos_detalles');
    }
}
