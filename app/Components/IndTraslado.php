<?php

namespace App\Components;

class IndTraslado
{
    public static $ind_traslados = [
        1 => 'Despacho por cuenta del receptor del documento (cliente o vendedor en caso de Facturas de compra.)',
        2 => 'Despacho por cuenta del emisor a instalaciones del cliente',
        3 => 'Despacho por cuenta del emisor a otras instalaciones (Ejemplo: entrega en Obra)',
    ];

    public static function getIndTrasladoText(int $indTraslado): string
    {
        if (! $indTraslado) {
            return 'CORREGIR EN TEMPLATE XSL';
        }

        $text = isset(self::$ind_traslados[$indTraslado]) ? self::$ind_traslados[$indTraslado] : 'CORREGIR EN TEMPLATE XSL';

        return $text;
    }
}
