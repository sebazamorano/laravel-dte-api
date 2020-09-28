<div class="receptor">
    @if($documento->receptor->RUTRecep != '' && $documento->receptor->RUTRecep != '66666666-6')
        <div id="contenedorReceptor">
            <table style="width:7cm;table-layout:fixed;border:1pt solid black;" id="contenedorDReceptor">
                <tbody>
                @if($documento->receptor->RznSocRecep != '' && $documento->receptor->RznSocRecep != 'CLIENTE OCACION')
                    <tr>
                        <td style="text-align:left; width:30%;">
                                    <span style="font-weight:bold;">
                                        SE&#209;OR(ES):
                                    </span>
                        </td>

                        <td style="text-align:left;">
                                    <span>
                                        {!! $documento->receptor->RznSocRecep !!}
                                    </span>
                        </td>
                    </tr>
                @endif

                @if($documento->receptor->RUTRecep != '' && $documento->receptor->RUTRecep != '66666666-6')
                    <tr>
                        <td style="text-align:left; width:1.5cm;">
                                    <span style="font-weight:bold">
                                        R.U.T.:
                                    </span>
                        </td>
                        <td style="text-align:left;">
                                    <span>
                                        {!! Freshwork\ChileanBundle\Rut::parse($documento->receptor->RUTRecep)->format() !!}
                                    </span>
                        </td>
                    </tr>
                @endif

                @if($documento->receptor->DirRecep != '')
                    <tr>
                        <td style="text-align:left; width:1.5cm;">
                                    <span style="font-weight:bold;">
                                        DIRECCION:
                                    </span>
                        </td>
                        <td style="text-align:left;">
                            {!! $documento->receptor->DirRecep !!}
                        </td>
                    </tr>
                @endif

                @if($documento->receptor->CmnaRecep != '')
                    <tr>
                        <td style="text-align:left; width:1.5cm;">
                                    <span style="font-weight:bold;">
                                        COMUNA:
                                    </span>
                        </td>
                        <td style="text-align:left;">
                            {!! $documento->receptor->CmnaRecep !!}
                        </td>
                    </tr>
                @endif

                @if($documento->receptor->GiroRecep != '')
                    <tr>
                        <td style="text-align:left; width:1.5cm;">
                                    <span style="font-weight:bold;">
                                        GIRO:
                                    </span>
                        </td>
                        <td style="text-align:left;">
                            {!! $documento->receptor->GiroRecep !!}
                        </td>
                    </tr>
                @endif

                @if($documento->receptor->CiudadRecep != '')
                    <tr>
                        <td style="text-align:left; width:1.5cm;">
                                    <span style="font-weight:bold;">
                                        CIUDAD:
                                    </span>
                        </td>
                        <td style="text-align:left;">

                            {!! $documento->receptor->CiudadRecep !!}
                        </td>
                    </tr>
                @endif

                <!--<xsl:if test="$medioPago != ''">
                    <tr>
                        <td style="text-align:left; width:1.5cm;">
                                <span style="font-weight:bold;">
                                    CONDICION VENTA:
                                </span>
                        </td>

                        <td style="text-align:left; width:5.5cm;">
                            <xsl:call-template name="PagoFormat">
                                <xsl:with-param name="medioPago">
                                    <xsl:value-of select="$medioPago" />
                                </xsl:with-param>
                                <xsl:with-param name="formaPago">
                                    <xsl:value-of select="$formaPago" />
                                </xsl:with-param>
                            </xsl:call-template>
                        </td>
                    </tr>
                </xsl:if>-->

                @if($documento->idDoc->IndTraslado != '')
                    <tr>
                        <td style="text-align:left; width:1.5cm;">
                            <span style="font-weight:bold;">
                                TIPO TRASLADO
                            </span>
                        </td>
                        <td style="text-align:left;">

                            {!! \App\Components\IndTraslado::getIndTrasladoText((int)$documento->idDoc->IndTraslado) !!}
                            @if($documento->idDoc->TipoDespacho != '')
                                <br/>
                                {!! \App\Components\TipoDespacho::getTipodDespachoText((int)$documento->idDoc->TipoDespacho) !!}
                            @endif
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>

        </div>
    @endif

    <div id="contenedorFechaEmision" style="margin-top:0.15cm;">
        <div id="fechaEmision">
            <span style="font-weight:bold;">Fecha de Emisión : </span>
            {!! $documento->idDoc->FchEmis->format('d-m-Y') !!}
            <br/>

            @if($documento->idDoc->FchVence != '')
                <span style="font-weight:bold;">Fecha de Vencimiento: </span>
                {!! $documento->idDoc->FchVence->format('d-m-Y') !!}
            @endif
        </div>
    </div>
</div>
