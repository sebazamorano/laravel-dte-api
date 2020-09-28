<?php
use App\Models\Documento;
/* @var Documento $documento */
$pdf417 = $documento->obtenerPDF417();
?>
<style>
    @page {
        margin: 0; /* <any of the usual CSS values for margins> */
        /*(% of page-box width for LR, of height for TB) */
        margin-header: 0; /* <any of the usual CSS values for margins> */
        margin-footer: 0; /* <any of the usual CSS values for margins> */
        marks: none;
    }

    .RutEmis{
        font-size:10pt;
        font-family: Arial, Helvetica, serif;
        font-weight:bold;
        color:black;
        text-align:center;
    }

    .TipoDTE{
        font-size:10pt;
        font-family: Arial, Helvetica, serif;
        font-weight:bold;
        color:black;
        text-align:center;
    }

    .Folio{
        font-size:10pt;
        font-family: Arial, Helvetica, serif;
        font-weight:bold;
        color:black;
        text-align:center;
    }

    .recuadroFolio{
        padding:0.5cm 0 0.5cm 0;
        border-color:black;
        border-style:solid;
        border-width:0.5mm;
    }

    .recuadroTipoDTE{
        width:7cm;
    }
    .recuadroUnidadSII{
        width:7cm;
        margin-top:1mm;
    }

    .unidadSII{
        font-size:10pt;
        font-family: Arial, Helvetica, serif;
        font-weight:bold;
        color:black;
        text-align:center;
    }

    .header{
        width:7cm;
        padding-bottom:0.15cm;
    }

    .RznSocActeco{
        width:7cm;
    }

    .casaMatriz{
        width:7cm;
        float:left;
    }

    body{
        font-size:8pt;
        font-family: Arial, Helvetica, serif;
    }

    .spanCasaMatriz{
        font-size:8pt;
        font-family: Arial, Helvetica, serif;
        color:black; text-align:left;
    }

    .contenedorLogotipo{
        width:7cm;
        height:auto;
        margin-top: 0.25cm;
        min-height: 1.5cm;
    }

    .hojaCarta{
        width:7cm;
        padding-top:0.5cm;
        padding-left:0.5cm;
        padding-right:0.5cm;
    }

    #contenedorFechaEmision{
        width:7cm;
    }

    #fechaEmision{
        font-size:8pt;
        font-family: Arial, Helvetica, serif;
        color:black; text-align:left;
    }

    #contenedorDReceptor{
        font-size:8pt;
        font-family: Arial, Helvetica, serif;
        color:black; text-align:left;
    }

    .items{
        text-align:left;
    }

    .centrado{
        text-align:center;
    }

    .itemsVacio{
        text-align:center;
    }

    #listaDetalle table{
        border-collapse:collapse;
    }

    #tablaDetalle{
        margin-top: 0.25cm;
    }

    #listaTotales table{
        border-collapse:collapse;
    }

    #tablaTotales{
        border:none;
        margin-top: 0.25cm;
    }

    #listaReferencias table{
        border-collapse:collapse;
    }

    #tablaReferencias{
        margin-top: 0.25cm;
    }

    #totales td{
        /* height:0.5cm;*/
        text-align:right;
    }

    .ted{
        padding-top:0.5cm;
        width:7cm;
        text-align:center;
    }

    #acuseRecibo tr{
        padding-top:1cm;
    }

    #acuseRecibo td{
        height:0.5cm;
    }
</style>

<div class="hojaCarta" id="hoja1">

    @include('pdfs.documento_termico_content')

    <div class="ted">
        <img style="width:7cm;height:4cm;" src="{{ $pdf417 }}"/>


        <div style="width:7cm">
            <span style="font-size:8pt; font-family:sans-serif;">
                Timbre Electrónico SII
            </span>
            <br/>
            <span style="font-size:8pt; font-family:sans-serif;">
                Res. {!! $documento->obtenerNumeroResolucion() !!} de {!! $documento->obtenerAnoResolucion() !!} - Verifique Documento: dsmgroup.cl/documentos
            </span>
        </div>
    </div>
</div>

@if($documento->idDoc->TipoDTE != 39 && $documento->idDoc->TipoDTE != 41)
<div class="hojaCarta" id="hoja2" style="page-break-before: always;">
    @include('pdfs.documento_termico_content')

    <div class="ted">
        <img style="width:7cm;height:4cm;" src="{{ $pdf417 }}"/>


        <div style="width:7cm">
            <span style="font-size:8pt; font-family:sans-serif;">
                Timbre Electrónico SII
            </span>
            <br/>
            <span style="font-size:8pt; font-family:sans-serif;">
                Res. {!! $documento->obtenerNumeroResolucion() !!} de {!! $documento->obtenerAnoResolucion() !!} - Verifique Documento: dsmgroup.cl/documentos
            </span>
        </div>
    </div>
</div>
@endif

@if($documento->idDoc->TipoDTE != 39 && $documento->idDoc->TipoDTE != 41 && $documento->idDoc->TipoDTE != 56 && $documento->idDoc->TipoDTE != 61 && ($documento->idDoc->IndTraslado != 5 && $documento->idDoc->IndTraslado != 6))
<div class="hojaCarta" id="hoja3" style="page-break-before: always;">
    @include('pdfs.documento_termico_content')

    <div style="border: 1px solid black;margin-top:10px">
        <table id="acuseRecibo" style="width:100%;font-family:Arial,Helvetica,sans-serif;color:black;font-size:8pt;">
            <tr>
                <td colspan="4" style="text-align:center;">ACUSE DE RECIBO</td>
            </tr>
            <tr>
                <td>NOMBRE</td>
                <td colspan="3" style="border-bottom:1pt solid black">&#160;</td>
            </tr>
            <tr>
                <td>R.U.T</td>
                <td width="30%" style="border-bottom:1pt solid black">&#160;</td>
                <td>FIRMA</td>
                <td width="30%" style="border-bottom:1pt solid black">&#160;</td>
            </tr>
            <tr>
                <td>FECHA</td>
                <td style="border-bottom:1pt solid black">&#160;</td>
                <td>RECINTO</td>
                <td style="border-bottom:1pt solid black">&#160;</td>
            </tr>
        </table>

        <table style="margin-top:10px;font-family:Arial,Helvetica,sans-serif;color:black;font-size:7pt;border-top: 1pt solid black;">
            <tr>
                <td>
                    EL ACUSE RECIBO QUE SE DECLARA EN ESTE ACTO, DE ACUERDO A LO

                    DISPUESTO EN LA LETRA b) DEL Art.4 Y LA LETRA c) DEL Art.5 DE LA LEY

                    19.983, ACREDITA QUE LA ENTREGA DE MERCADERIA(S) O SERVICIO(S)

                    PRESTADO(S) HA(N) SIDO RECIBIDO(S) EN TOTAL CONFORMIDAD.
                </td>
            </tr>
        </table>
    </div>

    <div class="ted">
        <img style="height:4cm;" src="{{ $pdf417 }}"/>

        <div style="width:7cm">
            <span style="font-size:8pt; font-family:sans-serif;">
                Timbre Electrónico SII
            </span>

            <br/>

            <span style="font-size:8pt; font-family:sans-serif;">
                Res. {!! $documento->obtenerNumeroResolucion() !!} de {!! $documento->obtenerAnoResolucion() !!} - Verifique Documento: dsmgroup.cl/documentos
            </span>

            <p style="font-size:10pt;font-family:Arial,Helvetica,sans-serif;color:black;font-weight:bold;text-align:right;">
                CEDIBLE
                @if($documento->idDoc->TipoDTE == 52)
                    CON SU FACTURA
                @endif
            </p>
        </div>
    </div>
</div>
@endif
