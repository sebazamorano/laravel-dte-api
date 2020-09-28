<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.1"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
>
    <xsl:decimal-format name="chilean" decimal-separator="," grouping-separator="."/>

    <xsl:output method="xml" version="1.0" omit-xml-declaration="yes"
                    indent="yes" encoding="ISO-8859-1"/>

    <xsl:template match="/">
        <div class="hojaCarta" id="hoja1">
        <xsl:apply-templates select="DTE/*[1]" />


            <div class="ted">
                <xsl:call-template name="TED" />

                <div style="width:7cm">
                <span style="font-size:8pt; font-family:sans-serif;">
                    Timbre Electrónico SII
                </span>
                <br></br>
                <span style="font-size:8pt; font-family:sans-serif;">
                    Res. var:numero_resolucion de var:ano_resolucion - Verifique Documento: var:web_verificacion
                </span>
                </div>
            </div>
        </div>
        <!-- FINAL HOJA 1 -->


        <!-- INICIO HOJA 2 -->
        <xsl:if test="/DTE/*[1]/Encabezado/IdDoc/TipoDTE != '39' and /DTE/*[1]/Encabezado/IdDoc/TipoDTE != '41'">
        <div class="hojaCarta" id="hoja2" style="page-break-before: always;">
              <xsl:apply-templates select="DTE/*[1]" />

            <div class="ted">
                <xsl:call-template name="TED" />

                <div style="width:7cm">
                <span style="font-size:8pt; font-family:sans-serif;">
                    Timbre Electrónico SII
                </span>
                <br></br>
                <span style="font-size:8pt; font-family:sans-serif;">
                    Res. var:numero_resolucion de var:ano_resolucion - Verifique Documento: var:web_verificacion
                </span>
                </div>
            </div>
        </div>
        </xsl:if>
        <!-- FINAL HOJA 2 56 61-->
		<xsl:choose>
        <xsl:when test="/DTE/*[1]/Encabezado/IdDoc/TipoDTE !=39 and /DTE/*[1]/Encabezado/IdDoc/TipoDTE != '41' and /DTE/*[1]/Encabezado/IdDoc/TipoDTE !=56 and /DTE/*[1]/Encabezado/IdDoc/TipoDTE !=61 and not(/DTE/*[1]/Encabezado/IdDoc/IndTraslado) or (/DTE/*[1]/Encabezado/IdDoc/IndTraslado!=5 and /DTE/*[1]/Encabezado/IdDoc/IndTraslado!=6)">
		<!-- INICIO HOJA 3 -->
		<pagebreak>
            <div class="hojaCarta" id="hoja3">
            <xsl:apply-templates select="DTE/*[1]" />

                <div style="width:7cm;margin-top:0.25cm;border:1pt solid black;">
                        <table id="acuseRecibo" style="padding-bottom:0.5cm;width:7cm;font-family:Arial,Helvetica;color:black;font-size:8pt;">
                                <tr>
                                        <td colspan="4" style="text-align:center;">ACUSE DE RECIBO</td>
                                </tr>
                                <tr>
                                        <td style="width:1cm;">NOMBRE</td>
                                        <td colspan="3" style="height:1cm;border-bottom:1pt solid black">&#160;</td>
                                </tr>
                                <tr>
                                        <td style="width:1cm;">R.U.T</td>
                                        <td style="height:1cm;width:2.5cm; border-bottom:1pt solid black">&#160;</td>
                                        <td>FIRMA</td>
                                        <td style="height:1cm;width:2.5cm; border-bottom:1pt solid black">&#160;</td>
                                </tr>
                                <tr>
                                        <td style="width:1cm;">FECHA</td>
                                        <td style="height:1cm;width:2.5cm; border-bottom:1pt solid black">&#160;</td>
                                        <td style="width:1cm;">RECINTO</td>
                                        <td style="height:1cm;width:2.5cm; border-bottom:1pt solid black">&#160;</td>
                                </tr>

                        </table>
                        <table style="width:7cm;font-family:Arial,Helvetica;color:black;font-size:7pt;border-top: 1pt solid black;">
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

            <div class="ted" style="width:7cm;">
                <xsl:call-template name="TED" />

                <div style="width:7cm">
                <span style="font-size:8pt; font-family:Arial, Helvetica, sans-serif;">
                    Timbre Electrónico SII
                </span>
                <br></br>
                <span style="font-size:8pt; font-family:Arial, Helvetica, sans-serif;">
                    Res. var:numero_resolucion de var:ano_resolucion - Verifique Documento: var:web_verificacion
                </span>
                            <p style="font-size:10pt;font-family:Arial,Helvetica;color:black;font-weight:bold;text-align:right;">
                                CEDIBLE <xsl:if test="/DTE/*[1]/Encabezado/IdDoc/TipoDTE = '52'">CON SU FACTURA</xsl:if>
                            </p>
                </div>
            </div>
            </div>
	</pagebreak>
				<!-- FINAL HOJA 3 -->
        </xsl:when>
		</xsl:choose>
    </xsl:template>

    <xsl:template match="DTE/*[1]">
        <xsl:param name="montoTotal">
             <xsl:value-of select="Encabezado/Totales/MntTotal"/>
        </xsl:param>
        <xsl:param name="IVA">
             <xsl:value-of select="Encabezado/Totales/IVA"/>
        </xsl:param>
        <xsl:param name="netofinal">
             <xsl:value-of select="Encabezado/Totales/MntNeto"/>
        </xsl:param>
        <xsl:param name="exento">
             <xsl:value-of select="Encabezado/Totales/MntExe"/>
        </xsl:param>
        <xsl:apply-templates select="Encabezado/Emisor">
            <xsl:with-param name="folio">
                <xsl:value-of select="Encabezado/IdDoc/Folio" />
            </xsl:with-param>
            <xsl:with-param name="tipo">
                <xsl:value-of select="Encabezado/IdDoc/TipoDTE" />
            </xsl:with-param>
        </xsl:apply-templates>
        <xsl:apply-templates select="Encabezado/Receptor">
            <xsl:with-param name="fecha">
                <xsl:value-of select="Encabezado/IdDoc/FchEmis" />
            </xsl:with-param>
            <xsl:with-param name="medioPago">
                <xsl:value-of select="Encabezado/IdDoc/MedioPago" />
            </xsl:with-param>
            <xsl:with-param name="formaPago">
                <xsl:value-of select="Encabezado/IdDoc/FmaPago" />
            </xsl:with-param>
        </xsl:apply-templates>

    <xsl:choose>
        <xsl:when test="Referencia">
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
                        <xsl:for-each select="Referencia">
                            <tr>
                                <td>
                                    <span style="font-weight:bold;">
                                        Tipo Documento
                                    </span>
                                </td>
                                <td colspan="3">
                                    <span>
                                        <xsl:choose>
                                            <xsl:when test="TpoDocRef=30">
                                                FACTURA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=32">
                                                FACTURA DE VENTA BIENES Y SERVICIOS NO AFECTO O EXENTOS DE IVA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=33">
                                                FACTURA ELECTRÓNICA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=34">
                                                FACTURA NO AFECTA O EXENTA ELECTRÓNICA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=35">
                                                BOLETA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=38">
                                                BOLETA EXENTA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=39">
                                                BOLETA ELECTÓNICA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=40">
                                                LIQUIDACIÓN FACTURA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=41">
                                                BOLETA EXENTA ELECTRÓNICA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=43">
                                                LIQUIDACIÓN-FACTURA ELECTRÓNICA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=45">
                                                FACTURA DE COMPRA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=46">
                                            FACTURA DE COMPRA ELECTRÓNICA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=50">
                                                GUÍA DE DESPACHO
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=52">
                                                GUÍA DE DESPACHO ELECTRÓNICA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=55">
                                                NOTA DE DÉBITO
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=56">
                                                NOTA DE DÉBITO ELECTRÓNICA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=60">
                                                NOTA DE CREDITO
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=61">
                                                NOTA DE CRÉDITO ELECTRÓNICA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=103">
                                                LIQUIDACIÓN
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=110">
                                                FACTURA DE EXPORTACIÓN ELECTRÓNICA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=111">
                                                NOTA DE DÉBITO DE EXPORTACIÓN ELECTRÓNICA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=112">
                                                NOTA DE CRÉDITO DE EXPORTACIÓN ELECTRÓNICA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=801">
                                                ORDEN DE COMPRA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=802">
                                                NOTA DE PEDIDO
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=803">
                                                CONTRATO
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=804">
                                                RESOLUCIÓN
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=805">
                                                PROCESO CHILECOMPRA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=806">
                                                FICHA CHILE COMPRA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=807">
                                                DUS
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=808">
                                                B/L (CONOCIMIENTO DE EMBARQUE)
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=809">
                                                AWB (AIR WILL BILL)
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=810">
                                                MIC/DTA
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=811">
                                                CARTA DE PORTE
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=812">
                                                RESOLUCIÓN DEL SNA DONDE CALIFICA SERVICIOS DE EXPORTACIÓN
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=813">
                                                PASAPORTE
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=814">
                                                CERTIFICADO DE DEPÓSITO BOLSA PROD. CHILE
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef=815">
                                                VALE DE PRENDA BOLSA PROD. CHILE
                                            </xsl:when>
                                            <xsl:when test="TpoDocRef='SET'">
                                                SET
                                            </xsl:when>
                                            <xsl:otherwise>
                                                 <xsl:value-of select='TpoDocRef'/>
                                            </xsl:otherwise>
                                        </xsl:choose>
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
                                        <xsl:value-of select="FolioRef" />
                                    </span>
                                </td>

                             <td>
                                <span style="font-weight:bold;">
                                    Fecha
                                </span>
                            </td>
                            <td>
                                <span>
                                    <xsl:value-of select="substring(FchRef,string-length(FchRef)-1,2)" />-<xsl:value-of select="substring(FchRef,string-length(FchRef)-4,2)" />-<xsl:value-of select="substring(FchRef,string-length(FchRef)-9,4)" />
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
                                        <xsl:value-of select='RazonRef'/>
                                    </span>
                                </td>

                            </tr>

                            <tr>
                                <td colspan="4" style="border-bottom:0.25mm solid black;"></td>
                            </tr>

                        </xsl:for-each>
                    </tbody>
                </table>
            </div>
        </xsl:when>
    </xsl:choose>

    <!--  La lista de detalle -->
    <div id="listaDetalle">
        <table id="tablaDetalle" style="width:100%;font-size:8pt; font-family:Arial,Helvetica;color:black; text-align:left;">
            <thead>
                <tr>
                    <td colspan="3" style="border-bottom:1pt solid black;">
                        <span style="font-weight:bold;">
                         Detalles
                        </span>
                    </td>
                </tr>

                <tr>
                    <td style="width:1cm;">
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
                    <td style="width:1.0cm; text-align:right;">
                        <span style="font-weight:bold;">
                            Cant.
                        </span>
                    </td>
                    <td style="width:1cm;text-align:right;">
                        <span style="font-weight:bold;">
                            Valor
                        </span>
                    </td>
                </tr>
            </thead>
            <tbody>
                <xsl:for-each select="Detalle">
                    <tr>
                        <td style="text-align:left;">
                            <span>
                                <xsl:value-of select="CdgItem/VlrCodigo" />
                            </span>

                            <span>
                                <xsl:value-of select="NmbItem" />
                                <br />
                                <xsl:value-of select="DscItem" />
                            </span>

                        </td>

                        <td  style="text-align:right;">
                            <span>
                                <xsl:value-of select='format-number(QtyItem, "#.###,####", "chilean")'/>
                            </span>
                        </td>

                        <td  style="text-align:right;">
                            <span>
                                <xsl:value-of select='format-number(MontoItem, "#.###,##", "chilean")'/>
                            </span>
                        </td>

                    </tr>

                    <tr>
                        <td>
                        <xsl:choose>
                            <xsl:when test="PrcItem != 0 and PrcItem != 'NaN'">
                               ($ <xsl:value-of select='format-number(PrcItem, "#.###,##", "chilean")'/> c/u)
                            </xsl:when>
                            </xsl:choose>
                        </td>
                    </tr>

                    <xsl:if test="sum(DescuentoMonto) != 0">
                    <tr>
                        <td colspan="3" style="border-bottom:0.5pt solid black;">
                            <span>

                                    ** DSCO <xsl:value-of select='format-number(DescuentoPct, "#.###,##", "chilean")'/>% - <xsl:value-of select='format-number(DescuentoMonto, "#.###,##", "chilean")'/>

                            </span>
                        </td>
                    </tr>
                    </xsl:if>

                </xsl:for-each>
            </tbody>
        </table>

        <table id="totales" style="font-size:8pt; font-family:Arial,Helvetica;color:black; text-align:left;">
            <xsl:for-each select="DscRcgGlobal">
                                    <xsl:choose>
                                        <xsl:when test="GlosaDR='Descuento Neto'">

                                            <xsl:if test="TpoValor='%'">

                                                    <tr>
                                                        <td>
                                                            <span style="font-weight:bold;">
                                                                    Desc. neto( <xsl:value-of select='ValorDR'/>%)
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span style="font-weight:bold;">
                                                                    <xsl:value-of select='format-number((($netofinal * 100) div (100-ValorDR)) - $netofinal, "#.###", "chilean")'/>
                                                            </span>
                                                        </td>
                                                    </tr>


                                            </xsl:if>

                                        </xsl:when>
                                        <xsl:otherwise>
                                            CORREGIR EN TEMPLATE XSL
                                        </xsl:otherwise>
                                    </xsl:choose>
            </xsl:for-each>

            <xsl:if test="sum(Detalle/DescuentoMonto) != 0">
                    <tr style="border-bottom:0.5pt solid black;">
                        <td>
                            <span style="font-weight:bold;">
                                Descuento
                            </span>
                        </td>
                        <td>
                            <span style="font-weight:bold;">
                                <xsl:value-of select='format-number(sum(Detalle/DescuentoMonto), "#.###,##", "chilean")'/>
                           </span>

                        </td>
                    </tr>
            </xsl:if>

            <xsl:choose>
                <xsl:when test="($exento!=0) and $exento != 'NaN' and $exento !=''">
                    <tr>
                        <td>
                            <span style="font-weight:bold;">
                                Exento
                            </span>
                        </td>
                        <td>
                            <span style="font-weight:bold;">
                                <xsl:value-of select='format-number($exento, "#.###,##", "chilean")'/>
                            </span>
                        </td>
                    </tr>
                </xsl:when>
            </xsl:choose>

            <xsl:choose>
                <xsl:when test="($netofinal!=0) and $netofinal != 'NaN' and $netofinal !=''">
                    <tr>
                        <td>
                            <span style="font-weight:bold;">
                                Neto
                            </span>
                        </td>
                        <td>
                            <span style="font-weight:bold;">
                                <xsl:value-of select='format-number($netofinal, "#.###,##", "chilean")'/>
                           </span>

                        </td>
                    </tr>
                </xsl:when>
            </xsl:choose>

            <xsl:choose>
                <xsl:when test="($IVA!=0) and $IVA != 'NaN' and $IVA !=''">
                    <tr>
                        <td>
                            <span style="font-weight:bold;">
                                IVA <xsl:value-of select="DTE/*[1]/Encabezado/Totales/TasaIVA"/>%
                            </span>
                        </td>
                        <td>
                            <span style="font-weight:bold;">
                                <xsl:value-of select='format-number($IVA, "#.###,##", "chilean")'/>
                            </span>
                        </td>
                    </tr>
                </xsl:when>
            </xsl:choose>

            <xsl:if test="sum(Encabezado/Totales/ImptoReten/MontoImp) != 0">
                <tr>
                    <td>
                        <span style="font-weight:bold;">Adicional</span>
                    </td>

                    <td>
                        <span style="font-weight:bold;">
                            <xsl:value-of select='format-number(sum(Encabezado/Totales/ImptoReten/MontoImp), "#.###,##", "chilean")' />
                        </span>
                    </td>
                </tr>
            </xsl:if>

            <tr>
                <td style="width:3cm;">
                    <span style="font-weight:bold;">
                        Total
                    </span>
                </td>
                <td style="width:2.5cm;">
                    <span style="font-weight:bold;">
                        <xsl:value-of select='format-number($montoTotal, "#.###,##", "chilean")'/>
                    </span>
                </td>
            </tr>
        </table>
    </div>

    </xsl:template>


    <!-- Datos del emisor -->
    <xsl:template match="Emisor">
        <xsl:param name="folio" />
        <xsl:param name="tipo" />

        <div class="recuadroTipoDTE">

            <!-- Recuadro con folio -->
            <div class="recuadroFolio">

                <div class="RutEmis">
                    R.U.T.:
                    <xsl:call-template name="RutFormat">
                        <xsl:with-param name="rut">
                            <xsl:value-of select="RUTEmisor" />
                        </xsl:with-param>
                    </xsl:call-template>
                </div>

                <br/>
                <div class="TipoDTE">
                    <xsl:choose>
                        <xsl:when test="$tipo=0">
                            VALE ELECTRÓNICO
                        </xsl:when>
                        <xsl:when test="$tipo=33">
                            FACTURA ELECTRÓNICA
                        </xsl:when>
                        <xsl:when test="$tipo=39">
                            BOLETA ELECTRÓNICA
                        </xsl:when>
                        <xsl:when test="$tipo=41">
                            BOLETA EXENTA ELECTRÓNICA
                        </xsl:when>
                        <xsl:when test="$tipo=52">
                            GUÍA DE DESPACHO ELECTRÓNICA
                        </xsl:when>
                        <xsl:when test="$tipo=56">
                            NOTA DE DÉBITO ELECTRÓNICA
                        </xsl:when>
                        <xsl:when test="$tipo=61">
                            NOTA DE CRÉDITO ELECTRÓNICA
                        </xsl:when>
                        <xsl:when test="$tipo=34">
                            FACTURA NO AFECTA O EXENTA ELECTRÓNICA
                        </xsl:when>
                        <xsl:otherwise>
                            CORREGIR EN TEMPLATE XSL
                        </xsl:otherwise>
                    </xsl:choose>
                </div>
                           <br/>
                <div class="Folio">
                    N°
                    <xsl:value-of select="$folio" />
                </div>
            </div>

            <div class="recuadroUnidadSII">

                <div class="unidadSII">
                    S.I.I - var:unidad_regional
                </div>

            </div>

        </div>

        <div class="header">

            <div class="RznSocActeco">
                var:logo1
                <div class="contenedorLogotipo" style="text-align:center;">
                    <img src="var:logotipo" style="width:170px;display:block;margin-left:auto;margin-right:auto;" />
                </div>
                var:logo2
                <div class="divRznSoc" style="margin-top:0.25cm">
                <span style="font-size:9pt; font-family:Arial,Helvetica;font-weight:bold; color:black;">
                    var:razon_social<!--<xsl:value-of select="RznSoc" />-->
                </span>
                </div>

                <div class="casaMatriz">
                    <!--
                    <xsl:if test="Sucursal">
                        <span class="spanCasaMatriz">Sucursal: <xsl:value-of select="Sucursal" /> (Codigo SII: <xsl:value-of select="CdgSIISucur" />)</span>
                        <br/>
                    </xsl:if>-->

                    <!--<span class="spanCasaMatriz">Casa matriz:
                        <xsl:value-of select="DirOrigen" />
                        ,
                        <xsl:value-of select="CmnaOrigen" />
                        ,
                        <xsl:value-of select="CiudadOrigen" />
                    </span>-->
                     <span class="spanCasaMatriz" style="font-weight:bold;">
                        var:sucursales
                    </span>
                </div>

                <div class="divActeco">
                    <span style="font-weight:bold;font-size:8pt;font-family:Arial,Helvetica;color:black;text-align:left;">
                        Giro:
                    </span>
                    <span class="acteco" style="font-size:8pt;font-family:Arial,Helvetica;color:black;text-align:left;">
                    <xsl:choose>
                        <xsl:when test="$tipo=39"><xsl:value-of select="GiroEmisor" /></xsl:when>
                        <xsl:otherwise>
                            <xsl:value-of select="GiroEmis" />
                        </xsl:otherwise>
                    </xsl:choose>
                    <br/>
                    </span>
                </div>

            </div>

        </div>

    </xsl:template>

    <!-- Datos del receptor -->

    <xsl:template match="Receptor">
        <xsl:param name="fecha" />
        <xsl:param name="medioPago"/>
        <xsl:param name="formaPago"/>

        <xsl:choose>
            <xsl:when test="RUTRecep != '' and RUTRecep != '66666666-6'">
                <div id="contenedorReceptor" style="width:7cm;">
                    <table style="width:7cm;border:1pt solid black;" id="contenedorDReceptor">

                        <tbody>

                            <xsl:choose>
                                <xsl:when test="RznSocRecep != '' and RznSocRecep != 'CLIENTE OCACION'">
                                    <tr>
                                        <td style="text-align:left; width:1.5cm;">
                                            <span style="font-weight:bold;">
                                                SE&#209;OR(ES):
                                            </span>
                                        </td>

                                        <td style="text-align:left; width:5.5cm;">
                                            <span>
                                                <xsl:value-of select="RznSocRecep" />
                                            </span>
                                        </td>
                                    </tr>
                                </xsl:when>
                            </xsl:choose>

                            <xsl:choose>
                                <xsl:when test="RUTRecep != '' and RUTRecep != '66666666-6'">
                                    <tr>
                                        <td style="text-align:left; width:1.5cm;">
                                            <span style="font-weight:bold">
                                                R.U.T.:
                                            </span>
                                        </td>
                                        <td style="text-align:left; width:5.5cm;">
                                            <span>
                                                <xsl:call-template name="RutFormat">
                                                    <xsl:with-param name="rut">
                                                        <xsl:value-of select="RUTRecep" />
                                                    </xsl:with-param>
                                                </xsl:call-template>
                                            </span>
                                        </td>

                                    </tr>
                                </xsl:when>
                            </xsl:choose>

                            <xsl:choose>
                                <xsl:when test="DirRecep != ''">
                                    <tr>
                                        <td style="text-align:left; width:1.5cm;">
                                            <span style="font-weight:bold;">
                                                DIRECCION:
                                            </span>
                                        </td>
                                        <td style="text-align:left; width:5.5cm;">
                                            <xsl:value-of select="DirRecep" />
                                        </td>
                                    </tr>
                                </xsl:when>
                            </xsl:choose>

                            <xsl:choose>
                                <xsl:when test="CmnaRecep != ''">
                                    <tr>
                                        <td style="text-align:left; width:1.5cm;">
                                            <span style="font-weight:bold;">
                                                COMUNA:
                                            </span>
                                        </td>
                                        <td style="text-align:left; width:5.5cm;">
                                            <xsl:value-of select="CmnaRecep" />
                                        </td>
                                    </tr>
                                </xsl:when>
                            </xsl:choose>

                            <xsl:choose>
                                <xsl:when test="CiudadRecep != ''">
                                    <tr>
                                        <td style="text-align:left; width:1.5cm;">
                                            <span style="font-weight:bold;">
                                                CIUDAD:
                                            </span>
                                        </td>
                                        <td style="text-align:left; width:5.5cm;">
                                            <xsl:value-of select="CiudadRecep" />
                                        </td>
                                    </tr>
                                </xsl:when>
                            </xsl:choose>

                            <xsl:choose>
                                <xsl:when test="GiroRecep != ''">
                                    <tr>
                                        <td style="text-align:left; width:1.5cm;">
                                            <span style="font-weight:bold;">
                                                GIRO:
                                            </span>
                                        </td>
                                        <td style="text-align:left; width:5.5cm;">
                                            <xsl:value-of select="GiroRecep" />
                                        </td>
                                    </tr>
                                </xsl:when>
                            </xsl:choose>

                            <xsl:if test="$medioPago != ''">
                            <tr>
                                <td style="text-align:left; width:1.5cm;">
                                    <span style="font-weight:bold;">
                                        CONDICION VENTA:
                                    </span>
                                </td>
                                <!--<td style="text-align:left; height:1cm;">-->
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
                            </xsl:if>

                           <xsl:if test="/DTE/*[1]/Encabezado/IdDoc/IndTraslado !=''">
                           <tr>
                                <td style="text-align:left; width:1.5cm;">
                                    <span style="font-weight:bold;">
                                        TIPO TRASLADO
                                    </span>
                                </td>
                                <!--<td style="text-align:left; height:1cm;">-->
                                <td style="text-align:left; width:5.5cm;">

                                                <xsl:choose>
                                                    <xsl:when test="/DTE/*[1]/Encabezado/IdDoc/IndTraslado=1">
                                                        Operación constituye venta
                                                    </xsl:when>
                                                    <xsl:when test="/DTE/*[1]/Encabezado/IdDoc/IndTraslado=2">
                                                        Ventas por efectuar
                                                    </xsl:when>
                                                    <xsl:when test="/DTE/*[1]/Encabezado/IdDoc/IndTraslado=3">
                                                        Consignaciones
                                                    </xsl:when>
                                                    <xsl:when test="/DTE/*[1]/Encabezado/IdDoc/IndTraslado=4">
                                                        Entrega gratuita
                                                    </xsl:when>
                                                    <xsl:when test="/DTE/*[1]/Encabezado/IdDoc/IndTraslado=5">
                                                        Traslados internos
                                                    </xsl:when>
                                                    <xsl:when test="/DTE/*[1]/Encabezado/IdDoc/IndTraslado=6">
                                                        Otros traslados no venta
                                                    </xsl:when>
                                                    <xsl:when test="/DTE/*[1]/Encabezado/IdDoc/IndTraslado=7">
                                                        Guía de devolución
                                                    </xsl:when>
                                                    <xsl:when test="/DTE/*[1]/Encabezado/IdDoc/IndTraslado=8">
                                                        Traslado para exportación. (no venta)
                                                    </xsl:when>
                                                    <xsl:when test="/DTE/*[1]/Encabezado/IdDoc/IndTraslado=9">
                                                        Venta para exportación
                                                    </xsl:when>
                                                    <xsl:otherwise>
                                                        CORREGIR EN TEMPLATE XSL
                                                    </xsl:otherwise>
                                                </xsl:choose>

                            <xsl:if test="/DTE/*[1]/Encabezado/IdDoc/TipoDespacho !=''">
                            <br></br>
                                <xsl:choose>
                                    <xsl:when test="/DTE/*[1]/Encabezado/IdDoc/TipoDespacho=1">
                                        Despacho por cuenta del receptor del documento (cliente o vendedor en caso de Facturas de compra.)
                                    </xsl:when>
                                    <xsl:when test="/DTE/*[1]/Encabezado/IdDoc/TipoDespacho=2">
                                        Despacho por cuenta del emisor a instalaciones del cliente
                                    </xsl:when>
                                    <xsl:when test="/DTE/*[1]/Encabezado/IdDoc/TipoDespacho=3">
                                       Despacho por cuenta del emisor a otras instalaciones (Ejemplo: entrega en Obra)
                                    </xsl:when>
                                    <xsl:otherwise>
                                        CORREGIR EN TEMPLATE XSL
                                    </xsl:otherwise>
                                </xsl:choose>
                            </xsl:if>

                                </td>
                            </tr>
                            </xsl:if>

                        </tbody>
                    </table>

                </div>
            </xsl:when>
        </xsl:choose>

        <div id="contenedorFechaEmision" style="margin-top:0.15cm;">
            <div id="fechaEmision">
                <span style="font-weight:bold;">Fecha de Emisión : </span>
                <xsl:call-template name="FechaFormat">
                    <xsl:with-param name="fecha">
                        <xsl:value-of select="$fecha" />
                    </xsl:with-param>
                </xsl:call-template>
                <br/>
                <xsl:if test="/DTE/*[1]/Encabezado/IdDoc/FchVenc !=''">
                    <span style="font-weight:bold;">Fecha de Vencimiento: </span>
                    <xsl:call-template name="FechaFormat">
                        <xsl:with-param name="fecha">
                            <xsl:value-of select="/DTE/*[1]/Encabezado/IdDoc/FchVenc" />
                        </xsl:with-param>
                    </xsl:call-template>
                </xsl:if>

            </div>
        </div>
    </xsl:template>

    <!-- Referencia -->
    <xsl:template match="Referencia">
    </xsl:template>
    <!-- Referencia -->

    <!-- Timbre electrónico -->
    <xsl:template name="TED">
        <!-- 4CM ORIGINAL-->
        <img style="height:4cm;" src="var:barcode"/>
    </xsl:template>

    <xsl:template name="PagoFormat">
        <xsl:param name="medioPago" />
        <xsl:param name="formaPago" />

        <xsl:choose>
            <xsl:when test="$medioPago='CH'">Cheque</xsl:when>
            <xsl:when test="$medioPago='LT'">Letra</xsl:when>
            <xsl:when test="$medioPago='EF'">Efectivo</xsl:when>
            <xsl:when test="$medioPago='PE'">Pago a Cta. Corriente</xsl:when>
            <xsl:when test="$medioPago='TC'">Tarjeta de Crédito</xsl:when>
            <xsl:when test="$medioPago='CF'">Cheque a Fecha</xsl:when>
            <xsl:when test="$medioPago='OT'">Otro</xsl:when>
        </xsl:choose>

        <xsl:choose>
            <xsl:when test="$formaPago=1"> (Contado)</xsl:when>
            <xsl:when test="$formaPago=2"> (Crédito)</xsl:when>
            <xsl:when test="$formaPago=3"> (Sin Valor)</xsl:when>
        </xsl:choose>

    </xsl:template>

    <xsl:template name="FechaFormat">
        <xsl:param name="fecha" />

        <xsl:value-of select="substring($fecha,string-length($fecha)-1,2)" />
        de
        <xsl:choose>
            <xsl:when
                test="substring($fecha,string-length($fecha)-4,2)=01">
                Enero
            </xsl:when>
            <xsl:when
                test="substring($fecha,string-length($fecha)-4,2)=02">
                Febrero
            </xsl:when>
            <xsl:when
                test="substring($fecha,string-length($fecha)-4,2)=03">
                Marzo
            </xsl:when>
            <xsl:when
                test="substring($fecha,string-length($fecha)-4,2)=04">
                Abril
            </xsl:when>
            <xsl:when
                test="substring($fecha,string-length($fecha)-4,2)=05">
                Mayo
            </xsl:when>
            <xsl:when
                test="substring($fecha,string-length($fecha)-4,2)=06">
                Junio
            </xsl:when>
            <xsl:when
                test="substring($fecha,string-length($fecha)-4,2)=07">
                Julio
            </xsl:when>
            <xsl:when
                test="substring($fecha,string-length($fecha)-4,2)=08">
                Agosto
            </xsl:when>
            <xsl:when
                test="substring($fecha,string-length($fecha)-4,2)=09">
                Septiembre
            </xsl:when>
            <xsl:when
                test="substring($fecha,string-length($fecha)-4,2)=10">
                Octubre
            </xsl:when>
            <xsl:when
                test="substring($fecha,string-length($fecha)-4,2)=11">
                Noviembre
            </xsl:when>
            <xsl:when
                test="substring($fecha,string-length($fecha)-4,2)=12">
                Diciembre
            </xsl:when>
        </xsl:choose>
        de
        <xsl:value-of
            select="substring($fecha,string-length($fecha)-9,4)" />
    </xsl:template>

    <xsl:template name="RutFormat">
        <xsl:param name="rut" />
        <xsl:variable name="num" select="substring-before($rut,'-')" />
        <xsl:variable name="dv" select="substring-after($rut,'-')" />
        <xsl:value-of select="substring($num,string-length($num)-8,3)" />.<xsl:value-of select="substring($num,string-length($num)-5,3)" />.<xsl:value-of select="substring($num,string-length($num)-2,3)" />-<xsl:value-of select="$dv" />
    </xsl:template>

</xsl:stylesheet>


