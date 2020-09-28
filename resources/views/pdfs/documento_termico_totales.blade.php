<?php
use App\Models\Documento;
/* @var Documento $documento */
?>
<div id="listaTotales" style="width:100%;position: relative;min-height: 1px;margin-left:1.5cm">

    <table id="tablaTotales" style="width:5.5cm;text-align:right;font-size:8pt; font-family:Arial,Helvetica,sans-serif;color:black;">
        @if($documento->detalle->sum('DescuentoMonto') > 0)
            <tr>
                <td>
                    <span style="font-weight:bold;">
                        Descuento
                    </span>
                </td>
                <td>
                    <span style="font-weight:bold;">
                        {!! number_format($documento->detalle->sum('DescuentoMonto'), 0, ',', '.') !!}
                   </span>

                </td>
            </tr>
        @endif

        @if($documento->totales->MntExe != 0 && $documento->totales->MntExe !== null)
            <tr>
                <td>
                    <span style="font-weight:bold;">
                        Exento
                    </span>
                </td>
                <td>
                    <span style="font-weight:bold;">
                        {!! number_format($documento->totales->MntExe, 0, ',', '.')  !!}
                    </span>
                </td>
            </tr>
        @endif

        @if($documento->tipoDocumento->tipoDTE != 39)
            @if($documento->totales->MntNeto != 0 && $documento->totales->MntNeto !== null)
                <tr>
                    <td>
                        <span style="font-weight:bold;">
                            Neto
                        </span>
                    </td>
                    <td>
                        <span style="font-weight:bold;">
                            {!! number_format($documento->totales->MntNeto, 0, ',', '.')  !!}
                       </span>
                    </td>
                </tr>
            @endif

            @if($documento->totales->IVA != 0 && $documento->totales->IVA !== null)
                <tr>
                    <td>
                        <span style="font-weight:bold;">
                            IVA {!! number_format((float)$documento->totales->TasaIVA, 0, ',', '.') !!}%
                        </span>
                    </td>
                    <td>
                        <span style="font-weight:bold;">
                            {!! number_format((float)$documento->totales->IVA, 0, ',', '.')  !!}
                        </span>
                    </td>
                </tr>
            @endif
        @endif

        <tr>
            <td>
                <span style="font-weight:bold;">
                    Total
                </span>
            </td>
            <td>
                <span style="font-weight:bold;">
                    {!! number_format((float)$documento->totales->MntTotal, 0, ',', '.')  !!}
                </span>
            </td>
        </tr>
    </table>

</div>

