<?php
use App\Models\Documento;
/* @var Documento $documento */
?>
@if(count($documento->detalle) > 0)
<div id="listaDetalle" style="width:7cm;">
    <table id="tablaDetalle" style="font-size:8pt; font-family:Arial,Helvetica,sans-serif;color:black; text-align:left;">
        <thead>
        <tr>
            <td colspan="3" style="border-bottom:1pt solid black;">
                <span style="font-weight:bold;">
                 Detalles
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span style="font-weight:bold;">
                    Articulo
                </span>
            </td>
        </tr>
        <tr>
            <td style="width:4cm;">
                <span style="font-weight:bold;">
                    Precio Unitario
                </span>
            </td>
            <td style="width:1.5cm; text-align:right;">
                <span style="font-weight:bold;">
                    Cant.
                </span>
            </td>
            <td style="width:1.5cm;text-align:right;">
                <span style="font-weight:bold;">
                    Valor
                </span>
            </td>
        </tr>
        </thead>
        <tbody>
        @foreach($documento->detalle as $detalle)
            @php
            /* @var App\Models\DocumentoDetalle $detalle */
            @endphp
            <tr>
                <td style="text-align:left;">
                    <span>
                        {!! $detalle->VlrCodigo !!}
                    </span>

                    <span>
                        {!! $detalle->NmbItem !!}
                        <br />
                        {!! $detalle->DscItem !!}
                    </span>
                </td>
                <td  style="text-align:right;">
                    <span>
                        {!! number_format($detalle->QtyItem, 0, ',', '.') !!}
                    </span>
                </td>
                <td  style="text-align:right;">
                    <span>
                        {!! number_format((float)$detalle->MontoItem, 0, ',', '.') !!}
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    @if($detalle->PrcItem != 0 && $detalle->PrcItem !== null)
                        ($  {!! number_format((float)$detalle->PrcItem, 0, ',', '.') !!} c/u)
                    @endif
                </td>
            </tr>

            @if($detalle->DescuentoMonto !== null && $detalle->DescuentoMonto > 0)
                <tr>
                    <td colspan="3" style="border-bottom:0.5pt solid black;">
                        <span>
                            ** DSCO {!! number_format((float)$detalle->DescuentoPct, 4, ',', '.') !!}% - {!! number_format((float)$detalle->DescuentoMonto, 4, ',', '.') !!}
                        </span>
                    </td>
                </tr>
            @endif

            @endforeach
        </tbody>
    </table>
</div>

@endif
