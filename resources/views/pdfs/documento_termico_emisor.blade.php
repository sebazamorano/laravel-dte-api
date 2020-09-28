<div class="recuadroTipoDTE">

    <!-- Recuadro con folio -->
    <div class="recuadroFolio">

        <div class="RutEmis">
            R.U.T.: {!! $documento->emisor->RUTEmisor !!}
        </div>

        <br/>
        <div class="TipoDTE">
            {!! mb_strtoupper($documento->tipoDocumento->nombre) !!}
        </div>
        <br/>
        <div class="Folio">
            N° {!! $documento->folio !!}
        </div>
    </div>

    <div class="recuadroUnidadSII">

        <div class="unidadSII">
            S.I.I - {!! App\Components\Sii::getDireccionRegional($documento->emisor->CmnaOrigen) !!}
        </div>

    </div>

</div>

<div class="header">

    <div class="RznSocActeco">

        <div class="divRznSoc" style="margin-top:0.25cm">
            <span style="font-size:9pt; font-family:Arial,Helvetica;font-weight:bold; color:black;">
                {!! $documento->empresa->razonSocial !!}
            </span>
        </div>

        <div class="casaMatriz">
            <span class="spanCasaMatriz" style="font-weight:bold;">
                    {!! $documento->textoSucursales() !!}
            </span>
        </div>

        <div class="divActeco">
            <span style="font-weight:bold;font-size:8pt;font-family:Arial,Helvetica;color:black;text-align:left;">
                Giro:
            </span>

            <span class="acteco" style="font-size:8pt;font-family:Arial,Helvetica;color:black;text-align:left;">
                {!! $documento->emisor->GiroEmis !!}
            </span>
        </div>

    </div>

</div>
