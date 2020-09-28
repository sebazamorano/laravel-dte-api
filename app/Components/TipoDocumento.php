<?php

namespace App\Components;

class TipoDocumento
{
    public static $tipos_documentos = [
        33 => 1, //Factura Electrónica
        34 => 2, //Factura No Afecta o Exenta Electrónica
        39 => 20, //Boleta Electrónica
        41 => 21, //Boleta Exenta Electrónica
        46 => 4, //Factura de Compra Electrónica
        52 => 5, //Guía de Despacho Electrónica
        56 => 6, //Nota de Débito Electrónica
        61 => 7, //Nota de Crédito Electrónica
    ];

    public static $id_tipos_documentos = [
        1 => 33, //Factura Electrónica
        2 => 34, //Factura No Afecta o Exenta Electrónica
        20 => 39, //Boleta Electrónica
        21 => 41, //Boleta Exenta Electrónica
        4 => 46, //Factura de Compra Electrónica
        5 => 52, //Guía de Despacho Electrónica
        6 => 56, //Nota de Débito Electrónica
        7 => 61, //Nota de Crédito Electrónica
    ];

    public static function getTipoDocumentoId(int $tipoDte)
    {
        if (! $tipoDte) {
            return 0;
        }

        $id = isset(self::$tipos_documentos[$tipoDte]) ? self::$tipos_documentos[$tipoDte] : 0;

        return $id;
    }

    public static function getTipoDocumentoXML(int $tipo_documento_id)
    {
        if (! $tipo_documento_id) {
            return 0;
        }

        $id = isset(self::$id_tipos_documentos[$tipo_documento_id]) ? self::$id_tipos_documentos[$tipo_documento_id] : 0;

        return $id;
    }
}
