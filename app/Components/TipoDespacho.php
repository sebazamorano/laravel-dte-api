<?php

namespace App\Components;

class TipoDespacho
{
    public static $tipos_despachos = [
        1 => 'Operación constituye venta',
        2 => 'Ventas por efectuar',
        3 => 'Consignaciones',
        4 => 'Entrega gratuita',
        5 => 'Traslados internos',
        6 => 'Otros traslados no venta',
        7 => 'Guía de devolución',
        8 => 'Traslado para exportación. (no venta)',
        9 => 'Venta para exportación',
    ];

    public static function getTipodDespachoText(int $tipoDespacho): string
    {
        if (! $tipoDespacho) {
            return 'CORREGIR EN TEMPLATE XSL';
        }

        $text = isset(self::$tipos_despachos[$tipoDespacho]) ? self::$tipos_despachos[$tipoDespacho] : 'CORREGIR EN TEMPLATE XSL';

        return $text;
    }
}
