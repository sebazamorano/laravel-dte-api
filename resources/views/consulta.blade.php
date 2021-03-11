@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="fusion-three-fifth fusion-layout-column fusion-column-last fusion-spacing-yes green-border rounded-border" style="margin-top:0px;margin-bottom:20px;">
                    <div class="fusion-column-wrapper" style="background-color:#ffffff;border:1px solid #e2e2e2;padding:60px 60px 50px 60px;">
                        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
                        <form class="bootstrap-form-with-validation" action="{{ route('api.consulta.pdf') }}" method="GET">
                            <input type="hidden" name="html_response" value="true">
                            <h2 class="text-center" data-fontsize="28" data-lineheight="34">Consultar DTE</h2>
                            <div class="form-group">
                                <label for="text-input" class="control-label">Rut Emisor</label>
                                <input type="text" name="rut" placeholder="RUT Emisor" pattern="\d{3,8}-[\d|kK]{1}" class="form-control" id="text-input" required="">
                            </div>
                            <div class="form-group">
                                <label for="folio-input" class="control-label">Folio Documento</label>
                                <input type="text" name="folio" placeholder="Folio Documento" required="" maxlength="15" class="form-control" pattern="\d*" id="folio-input">
                            </div>
                            <div class="form-group">
                                <label for="monto-input" class="control-label">Monto Total</label>
                                <input type="text" name="monto" placeholder="Monto Total" pattern="\d*" class="form-control" id="monto-input" required="" maxlength="15">
                            </div>

                            <div class="form-group">
                                <label for="tipodte-input" class="control-label">Tipo de Documento</label> <br>
                                <select name="tipo_documento_id" id="tipodte-input" required="" class="form-control">
                                    <option value="">Tipo de documento</option>
                                    <option value="1">Factura electrónica</option>
                                    <option value="2">Factura no afecta o exenta electrónica</option>
                                    <option value="7">Nota de crédito electrónica</option>
                                    <option value="6">Nota de débito electrónica</option>
                                    <option value="5">Guía de despacho electrónica</option>
                                    <option value="20">Boleta electrónica</option>
                                    <option value="21">Boleta no afecta o exenta electrónica</option>
                                </select></div>
                            <div class="form-group has-warning">
                                <button class="btn btn-primary" type="submit">Consultar</button>
                            </div>
                        </form>
                        <div class="fusion-clearfix">
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection
