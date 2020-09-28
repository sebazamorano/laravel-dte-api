<?php
use App\Models\Documento;
/* @var Documento $documento */
?>
@if(count($documento->referencia) > 0)
<div id="listaReferencias">
    <table id="tablaReferencias" style="width:7cm;font-size:7pt; font-family:Arial,Helvetica;color:black; text-align:left;">
        <thead>
        <tr>
            <td colspan="4" style="border-bottom:0.25mm solid black;">
                <span style="font-weight:bold;">
                Referencias a otros documentos
                </span>
            </td>
        </tr>
        </thead>
        <tbody>
        @foreach($documento->referencia as $referencia)
            <?php
                /* @var App\Models\DocumentoReferencia $referencia */
            ?>
            <tr>
                <td>
                    <span style="font-weight:bold;">
                        Tipo Documento
                    </span>
                </td>
                <td colspan="3">
                    <span>
                        {!! \App\Components\TipoDocumentoXml::getTipoDocumentoName($referencia->TpoDocRef) !!}
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight:bold;">
                        Folio
                    </span>
                </td>
                <td>
                    <span>
                        {!! $referencia->FolioRef !!}
                    </span>
                </td>

                <td>
                    <span style="font-weight:bold;">
                        Fecha
                    </span>
                </td>
                <td>
                    <span>
                        {!! $referencia->FchRef->format('d-m-Y') !!}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="width:2.2cm;">
                    <span style="font-weight:bold;">
                        Razón Referencia
                    </span>
                </td>
                <td colspan="3">
                    <span>
                        {!! $referencia->RazonRef !!}
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="border-bottom:0.25mm solid black;"></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif
