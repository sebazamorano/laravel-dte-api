<?php


namespace App\Components;


use App\Jobs\EnviarEnvioDteAlReceptor;
use App\Models\Caf;
use App\Models\Documento;
use App\Models\SII\ConsumoFolios\FolioConsumption;
use App\Repositories\DocumentoRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Certificador
{
    /** @var DocumentoRepository */
    private $documentoRepository;

    public function __construct()
    {
        $documentoRepo = new DocumentoRepository(app());
        $this->documentoRepository = $documentoRepo;
    }

    public static function setBoletasAfectas($fechaBoleta = null)
    {
        if($fechaBoleta == null){
            $fechaBoleta = Carbon::now()->format('Y-m-d');
        }

        $input = [];

        $input['CASO-1']['neto'] = 25042;
        $input['CASO-1']['iva'] = 4758;
        $input['CASO-1']['exento'] = 0;
        $input['CASO-1']['total'] = 29800;
        $input['CASO-1']['totales'] = [
            'MntNeto' => 25042,
            'TasaIVA' => 19.00,
            'IVA' => 4758,
            'MntTotal' => 29800
        ];
        $input['CASO-1']['tipo_documento_id'] = 20;
        $input['CASO-1']['idDoc']['TipoDTE'] = 39;
        $input['CASO-1']['detalle'] = [
            [
                'NroLinDet' => 1,
                'NmbItem' => 'Cambio de aceite',
                'QtyItem' => 1,
                'PrcItem' => 19900,
                'UnmdItem' => null,
                'MontoItem' => 19900,
                'IndExe' => 0,
            ],
            [
                'NroLinDet' => 2,
                'NmbItem' => 'Alineacion y balanceo',
                'QtyItem' => 1,
                'PrcItem' => 9900,
                'UnmdItem' => null,
                'MontoItem' => 9900,
                'IndExe' => 0,
            ]
        ];
        $input['CASO-1']['referencia'] = [
            [
                'NroLinRef' => 1,
                'TpoDocRef' => 'SET',
                'CodRef' => 0,
                'FolioRef' => '0',
                'FchRef' => $fechaBoleta,
                'RazonRef' => 'CASO-1',
            ]
        ];

        $input['CASO-2']['neto'] = 1714;
        $input['CASO-2']['iva'] = 326;
        $input['CASO-2']['exento'] = 0;
        $input['CASO-2']['total'] = 2040;
        $input['CASO-2']['totales'] = [
            'MntNeto' => 1714,
            'TasaIVA' => 19.00,
            'IVA' => 326,
            'MntTotal' => 2040
        ];
        $input['CASO-2']['tipo_documento_id'] = 20;
        $input['CASO-2']['idDoc']['TipoDTE'] = 39;
        $input['CASO-2']['Detalle'] =  [
            [
                'NroLinDet' => 1,
                'NmbItem' => 'Papel de regalo',
                'QtyItem' => 17,
                'PrcItem' => 120,
                'UnmdItem' => null,
                'MontoItem' => 2040,
                'IndExe' => 0,
            ],
        ];
        $input['CASO-2']['referencia'] = [
            [
                'NroLinRef' => 1,
                'TpoDocRef' => 'SET',
                'CodRef' => 0,
                'FolioRef' => '0',
                'FchRef' => $fechaBoleta,
                'RazonRef' => 'CASO-2',
            ]
        ];

        $input['CASO-3']['neto'] = 3445;
        $input['CASO-3']['iva'] = 655;
        $input['CASO-3']['exento'] = 0;
        $input['CASO-3']['total'] = 4100;
        $input['CASO-3']['totales'] = [
            'MntNeto' => 3445,
            'TasaIVA' => 19.00,
            'IVA' => 655,
            'MntTotal' => 4100
        ];
        $input['CASO-3']['tipo_documento_id'] = 20;
        $input['CASO-3']['idDoc']['TipoDTE'] = 39;
        $input['CASO-3']['Detalle'] = [
            [
                'NroLinDet' => 1,
                'NmbItem' => 'Sandwich',
                'QtyItem' => 2,
                'PrcItem' => 1500,
                'UnmdItem' => null,
                'MontoItem' => 3000,
                'IndExe' => 0,
            ],
            [
                'NroLinDet' => 2,
                'NmbItem' => 'Bebida',
                'QtyItem' => 2,
                'PrcItem' => 550,
                'UnmdItem' => null,
                'MontoItem' => 1100,
                'IndExe' => 0,
            ],
        ];
        $input['CASO-3']['referencia'] = [
            [
                'NroLinRef' => 1,
                'TpoDocRef' => 'SET',
                'CodRef' => 0,
                'FolioRef' => '0',
                'FchRef' => $fechaBoleta,
                'RazonRef' => 'CASO-3',
            ]
        ];

        $input['CASO-4']['neto'] = 10689;
        $input['CASO-4']['iva'] = 2031;
        $input['CASO-4']['exento'] = 2000;
        $input['CASO-4']['total'] = 14720;
        $input['CASO-4']['totales'] = [
            'MntNeto' => 10689,
            'MntExe' => 2000,
            'TasaIVA' => 19.00,
            'IVA' => 2031,
            'MntTotal' => 14720
        ];
        $input['CASO-4']['tipo_documento_id'] = 20;
        $input['CASO-4']['idDoc']['TipoDTE'] = 39;
        $input['CASO-4']['Detalle'] =  [
            [
                'NroLinDet' => 1,
                'NmbItem' => 'item afecto 1',
                'QtyItem' => 8,
                'PrcItem' => 1590,
                'UnmdItem' => null,
                'MontoItem' => 12720,
                'IndExe' => 0,
            ],
            [
                'NroLinDet' => 2,
                'NmbItem' => 'item exento 2',
                'QtyItem' => 2,
                'PrcItem' => 1000,
                'UnmdItem' => null,
                'MontoItem' => 2000,
                'IndExe' => 1,
            ],
        ];
        $input['CASO-4']['referencia'] = [
            [
                'NroLinRef' => 1,
                'TpoDocRef' => 'SET',
                'CodRef' => 0,
                'FolioRef' => '0',
                'FchRef' => $fechaBoleta,
                'RazonRef' => 'CASO-4',
            ]
        ];


        $input['CASO-5']['neto'] = 2941;
        $input['CASO-5']['iva'] = 559;
        $input['CASO-5']['exento'] = 0;
        $input['CASO-5']['total'] = 3500;
        $input['CASO-5']['totales'] = [
            'MntNeto' => 2941,
            'TasaIVA' => 19.00,
            'IVA' => 559,
            'MntTotal' => 3500
        ];
        $input['CASO-5']['tipo_documento_id'] = 20;
        $input['CASO-5']['idDoc']['TipoDTE'] = 39;
        $input['CASO-5']['Detalle']=  [
            [
                'NroLinDet' => 1,
                'NmbItem' => 'Arroz',
                'QtyItem' => 5,
                'PrcItem' => 700,
                'UnmdItem' => 'Kg',
                'MontoItem' => 3500,
                'IndExe' => 0,
            ],
        ];
        $input['CASO-5']['referencia'] = [
            [
                'NroLinRef' => 1,
                'TpoDocRef' => 'SET',
                'CodRef' => 0,
                'FolioRef' => '0',
                'FchRef' => $fechaBoleta,
                'RazonRef' => 'CASO-5',
            ]
        ];

        return $input;
    }

    public static function setBoletasExentas($fechaBoleta = null)
    {
        if($fechaBoleta == null){
            $fechaBoleta = Carbon::now()->format('Y-m-d');
        }

        $input = [];

        $input['CASO-1']['neto'] = 0;
        $input['CASO-1']['iva'] = 0;
        $input['CASO-1']['exento'] = 120000;
        $input['CASO-1']['total'] = 120000;
        $input['CASO-1']['totales'] = [
            'MntNeto' => 0,
            'MntExe' => 120000,
            'TasaIVA' => 19.00,
            'IVA' => 0,
            'MntTotal' => 120000
        ];
        $input['CASO-1']['tipo_documento_id'] = 21;
        $input['CASO-1']['idDoc']['TipoDTE'] = 41;
        $input['CASO-1']['detalle'] = [
            [
                'NroLinDet' => 1,
                'NmbItem' => 'Consultoría 1',
                'QtyItem' => 1,
                'PrcItem' => 50000,
                'UnmdItem' => null,
                'MontoItem' => 50000,
                'IndExe' => 1,
            ],
            [
                'NroLinDet' => 2,
                'NmbItem' => 'Consultoría 2',
                'QtyItem' => 2,
                'PrcItem' => 35000,
                'UnmdItem' => null,
                'MontoItem' => 70000,
                'IndExe' => 1,
            ]
        ];
        $input['CASO-1']['referencia'] = [
            [
                'NroLinRef' => 1,
                'TpoDocRef' => 'SET',
                'CodRef' => 0,
                'FolioRef' => '0',
                'FchRef' => $fechaBoleta,
                'RazonRef' => 'CASO-1',
            ]
        ];

        $input['CASO-2']['neto'] = 0;
        $input['CASO-2']['iva'] = 0;
        $input['CASO-2']['exento'] = 95000;
        $input['CASO-2']['total'] = 95000;
        $input['CASO-2']['totales'] = [
            'MntNeto' => 0,
            'MntExe' => 95000,
            'TasaIVA' => 19.00,
            'IVA' => 0,
            'MntTotal' => 95000
        ];
        $input['CASO-2']['tipo_documento_id'] = 21;
        $input['CASO-2']['idDoc']['TipoDTE'] = 41;
        $input['CASO-2']['Detalle'] =  [
            [
                'NroLinDet' => 1,
                'NmbItem' => 'Capacitación',
                'QtyItem' => 1,
                'PrcItem' => 95000,
                'UnmdItem' => 'mens',
                'MontoItem' => 95000,
                'IndExe' => 1,
            ],
        ];
        $input['CASO-2']['referencia'] = [
            [
                'NroLinRef' => 1,
                'TpoDocRef' => 'SET',
                'CodRef' => 0,
                'FolioRef' => '0',
                'FchRef' => $fechaBoleta,
                'RazonRef' => 'CASO-2',
            ]
        ];

        $input['CASO-3']['neto'] = 0;
        $input['CASO-3']['iva'] = 0;
        $input['CASO-3']['exento'] = 77500;
        $input['CASO-3']['total'] = 77500;
        $input['CASO-3']['totales'] = [
            'MntNeto' => 0,
            'MntExe' => 77500,
            'TasaIVA' => 19.00,
            'IVA' => 0,
            'MntTotal' => 77500,
        ];
        $input['CASO-3']['tipo_documento_id'] = 21;
        $input['CASO-3']['idDoc']['TipoDTE'] = 41;
        $input['CASO-3']['Detalle'] = [
            [
                'NroLinDet' => 1,
                'NmbItem' => 'Consulta Médica',
                'QtyItem' => 1,
                'PrcItem' => 25000,
                'UnmdItem' => null,
                'MontoItem' => 25000,
                'IndExe' => 1,
            ],
            [
                'NroLinDet' => 2,
                'NmbItem' => 'Atención Hospitalaria',
                'QtyItem' => 3,
                'PrcItem' => 17500,
                'UnmdItem' => null,
                'MontoItem' => 52500,
                'IndExe' => 1,
            ],
        ];
        $input['CASO-3']['referencia'] = [
            [
                'NroLinRef' => 1,
                'TpoDocRef' => 'SET',
                'CodRef' => 0,
                'FolioRef' => '0',
                'FchRef' => $fechaBoleta,
                'RazonRef' => 'CASO-3',
            ]
        ];

        return $input;
    }

    public function crearRcf($company, $fecha, $documentos)
    {
        /* @var FolioConsumption $reporte */
        $reporte = FolioConsumption::where('empresa_id', $company->id)->where('fchInicio', $fecha)->latest()->first();

        if($reporte !== null){
            $secEnvio = $reporte->secEnvio + 1;
        }else{
            $secEnvio = 1;
        }

        $folio = FolioConsumption::createInfo($company->id, $fecha, $secEnvio, true, $documentos);

        $xml = $folio->generarXml();
        $signed_xml = $folio->firmarXml($xml);
        $file = $folio->subirXml($signed_xml);
        $email_id = $folio->crearCorreoCertificacion($file->id);
        EnviarEnvioDteAlReceptor::dispatch($email_id);
    }

    public function crearBoleta($input, $fecha, $company)
    {
        /* @var Documento $documento */
        $input['empresa_id'] = $company->id;
        $input['fechaEmision'] = $fecha;
        $input['idDoc']['FchEmis'] = $fecha;
        $input['idDoc']['IndServicio'] = 3;
        $input['receptor']['CiudadRecep'] = "NO ESPECIFICADA";
        $input['receptor']['CmnaRecep'] = "NO ESPECIFICADA";
        $input['receptor']['DirRecep'] = "NO ESPECIFICADA";
        $input['receptor']['GiroRecep'] = "PARTICULAR";
        $input['receptor']['RUTRecep']	= "66666666-6";
        $input['receptor']['RznSocRecep'] = "PARTICULAR";

        $caf = new Caf();
        $folio = $caf->solicitarFolio($input['tipo_documento_id'], $company, null);
        $input['folio'] = $folio->folioUltimo;
        $input['caf_id'] = $folio->id;
        $input['idDoc']['Folio'] = $folio->folioUltimo;
        $input['referencia'][0]['FolioRef'] = (string) $folio->folioUltimo;
        $input = Documento::crearDatosEmisor($input);

        $documento = $this->documentoRepository->create($input);
        $documento = Documento::find($documento->id);

        $certificado = $documento->asignarCertificado();

        if (! $certificado) {
            DB::rollBack();
            request()->session()->flash('error-message', ['No se logro asignar certificado!']);
            return redirect()->back();
        }

        $xml_string = $documento->generarDTE();
        $xml = $documento->subirXmlDteS3($xml_string);
        $documento->archivos()->attach($xml->id, ['tipo' => TipoArchivo::DTE]);
        return $documento;
    }
}
