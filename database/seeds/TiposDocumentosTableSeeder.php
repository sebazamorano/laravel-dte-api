<?php

use App\Models\TipoDocumento;
use Illuminate\Database\Seeder;

class TiposDocumentosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoDocumento::Create([
            'nombre' => 'Factura Electrónica',
            'tipoDTE' => '33',
            'noAplica' => null,
            'nombreEnLibro' => 'Factura Electrónica',
            'xml' => true,
        ]);

        TipoDocumento::Create([
            'nombre' => 'Factura No Afecta o Exenta Electrónica',
            'tipoDTE' => '34',
            'noAplica' => null,
            'nombreEnLibro' => 'Factura No Afecta o Exenta Electrónica',
            'xml' => true,
        ]);

        TipoDocumento::Create([
            'nombre' => 'Liquidación - Factura Electrónica',
            'tipoDTE' => '43',
            'noAplica' => null,
            'nombreEnLibro' => 'Liquidación - Factura Electrónica',
            'xml' => true,
        ]);

        TipoDocumento::Create([
            'nombre' => 'Factura de Compra Electrónica',
            'tipoDTE' => '46',
            'noAplica' => null,
            'nombreEnLibro' => 'Factura de Compra Electrónica',
            'xml' => true,
        ]);

        TipoDocumento::Create([
            'nombre' => 'Guía de Despacho Electrónica',
            'tipoDTE' => '52',
            'noAplica' => null,
            'nombreEnLibro' => 'Guía de Despacho Electrónica',
            'xml' => true,
        ]);

        TipoDocumento::Create([
            'nombre' => 'Nota de Débito Electrónica',
            'tipoDTE' => '56',
            'noAplica' => null,
            'nombreEnLibro' => 'Nota de Débito Electrónica',
            'xml' => true,
        ]);

        TipoDocumento::Create([
            'nombre' => 'Nota de Crédito Electrónica',
            'tipoDTE' => '61',
            'noAplica' => null,
            'nombreEnLibro' => 'Nota de Crédito Electrónica',
            'xml' => true,
        ]);

        TipoDocumento::Create([
            'nombre' => 'Factura de Exportación Electrónica',
            'tipoDTE' => '110',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Factura de Exportación Electrónica',
            'xml' => true,
        ]);

        TipoDocumento::Create([
            'nombre' => 'Nota de Débito de Exportación Electrónica',
            'tipoDTE' => '111',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Nota de Débito de Exportación Electrónica',
            'xml' => true,
        ]);

        TipoDocumento::Create([
            'nombre' => 'Nota de Crédito de Exportación Electrónica',
            'tipoDTE' => '112',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Nota de Crédito de Exportación Electrónica',
            'xml' => true,
        ]);

        TipoDocumento::Create([
            'nombre' => 'Factura',
            'tipoDTE' => '30',
            'noAplica' => null,
            'nombreEnLibro' => 'Factura',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Factura de Ventas y Servicios no Afectos o Exentos de IVA',
            'tipoDTE' => '32',
            'noAplica' => null,
            'nombreEnLibro' => 'Factura de Ventas y Servicios no Afectos o Exentos de IVA',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Liquidación Factura',
            'tipoDTE' => '40',
            'noAplica' => null,
            'nombreEnLibro' => 'Liquidación Factura',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Factura de Compra',
            'tipoDTE' => '45',
            'noAplica' => null,
            'nombreEnLibro' => 'Factura de Compra',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Nota de Débito',
            'tipoDTE' => '55',
            'noAplica' => null,
            'nombreEnLibro' => 'Nota de Débito',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Nota de Crédito',
            'tipoDTE' => '60',
            'noAplica' => null,
            'nombreEnLibro' => 'Nota de Crédito',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Boleta',
            'tipoDTE' => '35',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Total operaciones del mes, con boleta (afecta)',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Boleta exenta',
            'tipoDTE' => '38',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Total operaciones del mes con boleta no afecta o exenta',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Liquidación',
            'tipoDTE' => '103',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Liquidación',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Boleta Electrónica',
            'tipoDTE' => '39',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Total operaciones del mes, con boleta electrónica',
            'xml' => true,
        ]);

        TipoDocumento::Create([
            'nombre' => 'Boleta Exenta Electrónica',
            'tipoDTE' => '41',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Total operaciones del mes, con boleta no afecta o exenta electrónica',
            'xml' => true,
        ]);

        TipoDocumento::Create([
            'nombre' => 'Guía de Despacho',
            'tipoDTE' => '50',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Guía de Despacho',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Factura de Inicio',
            'tipoDTE' => '56',
            'noAplica' => null,
            'nombreEnLibro' => 'Factura de Inicio',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Factura de exportación',
            'tipoDTE' => '101',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Factura de exportación',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Factura de venta exenta a zona franca primaria (Res. Ex. N° 601 de 07.05.82)',
            'tipoDTE' => '102',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Factura de venta exenta a zona franca primaria (Res. Ex. N° 601 de 07.05.82)',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Nota de débito de exportación',
            'tipoDTE' => '104',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Nota de débito de exportación',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Boleta liquidación (Res. Ex. N° 1423 del 23.12.76)',
            'tipoDTE' => '105',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Boleta liquidación (Res. Ex. N° 1423 del 23.12.76)',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Nota de Crédito de exportación',
            'tipoDTE' => '106',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Nota de Crédito de exportación',
        ]);

        TipoDocumento::Create([
            'nombre' => 'SRF Solicitud Registro de Factura',
            'tipoDTE' => '108',
            'noAplica' => null,
            'nombreEnLibro' => 'SRF Solicitud Registro de Factura',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Factura a turista (Res. Ex. N° 6428 de 06.12.93)',
            'tipoDTE' => '109',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Factura a turista (Res. Ex. N° 6428 de 06.12.93)',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Orden de Compra',
            'tipoDTE' => '801',
            'noAplica' => null,
            'nombreEnLibro' => 'Orden de Compra',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Nota de pedido',
            'tipoDTE' => '802',
            'noAplica' => null,
            'nombreEnLibro' => 'Nota de pedido',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Contrato',
            'tipoDTE' => '803',
            'noAplica' => null,
            'nombreEnLibro' => 'Contrato',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Resolución',
            'tipoDTE' => '804',
            'noAplica' => null,
            'nombreEnLibro' => 'Resolución',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Proceso ChileCompra',
            'tipoDTE' => '805',
            'noAplica' => null,
            'nombreEnLibro' => 'Proceso ChileCompra',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Ficha ChileCompra',
            'tipoDTE' => '806',
            'noAplica' => null,
            'nombreEnLibro' => 'Ficha ChileCompra',
        ]);

        TipoDocumento::Create([
            'nombre' => 'AWB (Air Will Bill)',
            'tipoDTE' => '809',
            'noAplica' => null,
            'nombreEnLibro' => 'AWB (Air Will Bill)',
        ]);

        TipoDocumento::Create([
            'nombre' => 'MIC/DTA',
            'tipoDTE' => '810',
            'noAplica' => null,
            'nombreEnLibro' => 'MIC/DTA',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Carta de Porte',
            'tipoDTE' => '811',
            'noAplica' => null,
            'nombreEnLibro' => 'Carta de Porte',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Resolución del SNA donde califica Servicios de Exportación',
            'tipoDTE' => '812',
            'noAplica' => null,
            'nombreEnLibro' => 'Resolución del SNA donde califica Servicios de Exportación',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Pasaporte',
            'tipoDTE' => '813',
            'noAplica' => null,
            'nombreEnLibro' => 'Pasaporte',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Vale de Prenda Bolsa Prod. Chile',
            'tipoDTE' => '814',
            'noAplica' => null,
            'nombreEnLibro' => 'Vale de Prenda Bolsa Prod. Chile',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Certificado de Depósito Bolsa Prod. Chile.',
            'tipoDTE' => '815',
            'noAplica' => null,
            'nombreEnLibro' => 'Certificado de Depósito Bolsa Prod. Chile.',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Factura de ventas a empresas del territorio preferencial ( Res. Ex. N°1057, del 25.04.85)',
            'tipoDTE' => '901',
            'noAplica' => null,
            'nombreEnLibro' => 'Factura de ventas a empresas del territorio preferencial ( Res. Ex. N°1057, del 25.04.85)',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Conocimiento de Embarque (Marítimo o aéreo)',
            'tipoDTE' => '902',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Conocimiento de Embarque (Marítimo o aéreo)',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Documento único de Salida (DUS)',
            'tipoDTE' => '903',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Documento único de Salida (DUS)',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Resumen ventas de nacionales pasajes sin Factura',
            'tipoDTE' => '919',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Resumen ventas de nacionales pasajes sin Factura',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Otros registros no documentados Aumenta débito',
            'tipoDTE' => '920',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Otros registros no documentados Aumenta débito',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Otros registros. Disminuye débito.',
            'tipoDTE' => '922',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Otros registros. Disminuye débito.',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Resumen ventas de internacionales pasajes sin Factura',
            'tipoDTE' => '924',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Resumen ventas de internacionales pasajes sin Factura',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Declaración de Ingreso (DIN)',
            'tipoDTE' => '914',
            'noAplica' => 'VENTAS',
            'nombreEnLibro' => 'Declaración de Ingreso (DIN)',
        ]);

        TipoDocumento::Create([
            'nombre' => 'Declaración de Ingreso a Zona Franca Primaria.',
            'tipoDTE' => '911',
            'noAplica' => 'COMPRA',
            'nombreEnLibro' => 'Declaración de Ingreso a Zona Franca Primaria.',
        ]);
    }
}
