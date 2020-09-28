<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->unsignedBigInteger('tipo_documento_id');
            $table->unsignedBigInteger('caf_id')->nullable();
            $table->unsignedBigInteger('certificado_empresa_id')->nullable();
            $table->text('observaciones')->nullable();
            $table->date('fechaEmision');
            $table->unsignedInteger('folio')->nullable();
            $table->string('folioString', 100)->nullable();
            $table->integer('tipo_pago_id')->unsigned()->nullable();
            $table->integer('tipo_plazo_id')->unsigned()->nullable();
            $table->date('fechaVencimiento')->nullable();
            $table->bigInteger('neto')->nullable();
            $table->bigInteger('exento')->nullable();
            $table->bigInteger('iva')->nullable();
            $table->bigInteger('total');
            $table->integer('costo')->nullable();
            $table->integer('margen')->nullable();
            $table->bigInteger('saldo')->nullable();
            $table->boolean('cancelado')->nullable();
            $table->date('fechaPago')->nullable();
            $table->text('nota')->nullable();
            $table->datetime('TSTED')->nullable();
            $table->datetime('TmstFirma')->nullable();
            $table->tinyInteger('IO')->default(0)->comment('Entrada/salida documentos 0 = Out salidas Ventas. 1 = In entrada.');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedInteger('estado_adicional')->nullable();
            $table->unsignedInteger('estadoPago')->nullable();
            $table->integer('estado')->default(1)->comment('0 BORRADOR , 1 GENERADO, 2 ANULADO');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
            $table->foreign('tipo_documento_id')->references('id')->on('tipos_documentos');
            $table->foreign('caf_id')->references('id')->on('cafs');
            $table->foreign('certificado_empresa_id')->references('id')->on('certificados_empresas');
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
        Schema::drop('documentos');
    }
}
