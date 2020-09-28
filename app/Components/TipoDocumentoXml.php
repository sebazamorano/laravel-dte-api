<?php

namespace App\Components;

class TipoDocumentoXml
{
    public static $tipos_documentos = [
        30 => 'FACTURA',
        32 => 'FACTURA DE VENTA BIENES Y SERVICIOS NO AFECTO O EXENTOS DE IVA',
        33 => 'FACTURA ELECTRÓNICA',
        34 => 'FACTURA NO AFECTA O EXENTA ELECTRÓNICA',
        35 => 'BOLETA',
        38 => 'BOLETA EXENTA',
        39 => 'BOLETA ELECTÓNICA',
        40 => 'LIQUIDACIÓN FACTURA',
        41 => 'BOLETA EXENTA ELECTRÓNICA',
        43 => 'LIQUIDACIÓN-FACTURA ELECTRÓNICA',
        45 => 'FACTURA DE COMPRA',
        46 => 'FACTURA DE COMPRA ELECTRÓNICA',
        50 => 'GUÍA DE DESPACHO',
        52 => 'GUÍA DE DESPACHO ELECTRÓNICA',
        55 => 'NOTA DE DÉBITO',
        56 => 'NOTA DE DÉBITO ELECTRÓNICA',
        60 => 'NOTA DE CREDITO',
        61 => 'NOTA DE CRÉDITO ELECTRÓNICA',
        103 => 'LIQUIDACIÓN',
        110 => 'FACTURA DE EXPORTACIÓN ELECTRÓNICA',
        111 => 'NOTA DE DÉBITO DE EXPORTACIÓN ELECTRÓNICA',
        112 => 'NOTA DE CRÉDITO DE EXPORTACIÓN ELECTRÓNICA',
        801 => 'ORDEN DE COMPRA',
        802 => 'NOTA DE PEDIDO',
        803 => 'CONTRATO',
        804 => 'RESOLUCIÓN',
        805 => 'PROCESO CHILECOMPRA',
        806 => 'FICHA CHILE COMPRA',
        807 => 'DUS',
        808 => 'B/L (CONOCIMIENTO DE EMBARQUE)',
        809 => 'AWB (AIR WILL BILL)',
        810 => 'MIC/DTA',
        811 => 'CARTA DE PORTE',
        812 => 'RESOLUCIÓN DEL SNA DONDE CALIFICA SERVICIOS DE EXPORTACIÓN',
        813 => 'PASAPORTE',
        814 => 'CERTIFICADO DE DEPÓSITO BOLSA PROD. CHILE',
        815 => 'VALE DE PRENDA BOLSA PROD. CHILE',
    ];

    public static function getTipoDocumentoName($tipoDte)
    {
        if (! $tipoDte) {
            return 0;
        }

        $name = isset(self::$tipos_documentos[$tipoDte]) ? self::$tipos_documentos[$tipoDte] : $tipoDte;

        return $name;
    }
}
