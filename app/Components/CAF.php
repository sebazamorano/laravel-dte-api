<?php

namespace App\Components;

use App\File;
use App\Models\Empresa;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Storage;

class CAF
{
    /**
     * Entrega un arreglo con los datos del caf rut, td, desde, hasta, fa.
     *
     * @param    string  $path el path del archivo de codigo de autorización de folios
     * @author Joaquín Gamboa Figueroa, joaquin.gamboaf@gmail.com
     * @return   array['rut', 'td', 'd', 'h', 'fa]
     */
    public static function leerCAF(string $path): array
    {
        $internal_errors = libxml_use_internal_errors(true);
        $CAF_XML = new \DOMDocument();
        $CAF_XML->formatOutput = false;
        $CAF_XML->preserveWhiteSpace = true;

        if (! $CAF_XML->load($path)) {
            $CAF_ENCODED = utf8_encode(file_get_contents($path));
            $CAF_XML->loadXML($CAF_ENCODED);
        }

        return [
            'rut' => $rutEmpresa = $CAF_XML->getElementsByTagName('RE')->item(0)->nodeValue,
            'td' => $CAF_XML->getElementsByTagName('TD')->item(0)->nodeValue,
            'desde' => $CAF_XML->getElementsByTagName('D')->item(0)->nodeValue,
            'hasta' => $CAF_XML->getElementsByTagName('H')->item(0)->nodeValue,
            'fa' => $CAF_XML->getElementsByTagName('FA')->item(0)->nodeValue,
        ];
    }

    public static function verificarMaximoAutorizado($tipo_dte, Empresa $empresa)
    {
        libxml_use_internal_errors(true);
        $sii = new Sii($empresa);
        $certificado = $empresa->descargarCertificadoPem();
        $cookieJar = $sii->obtenerCookies($certificado[1], $certificado[2]);
        $empresa->borrarCertificado($certificado[0]);

        $client = new \GuzzleHttp\Client();
        $rut = explode("-", $empresa->rut);

        $query_string = ['RUT_EMP' => $rut[0], 'DV_EMP' => $rut[1], 'COD_DOCTO' => $tipo_dte, 'ACEPTAR' => 'Continuar'];

        $response = $client->post($sii->generarUrlSolicitaFoliosDcto(),
            [
                'form_params' => $query_string,
                'headers' => [
                    'User-Agent' => Sii::USER_AGENT,
                    'Accept' => '*/*',
                    'Referer' => $sii->generarUrlSolicitaFolios(),
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'cookies' => $cookieJar,
                'curl' => [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_PORT => 443,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                ],
            ]);
        $contents = $response->getBody()->getContents();

        $dom = new \DOMDocument();
        $dom->loadHTML($contents);
        $solicitud = [];

        foreach($dom->getElementsByTagName('input') as $node)
        {
            $solicitud[$node->getAttribute('name')] = $node->getAttribute('value');
        }

        return $solicitud;
    }

    public static function timbraje($tipo_dte, Empresa $empresa, $cantidad)
    {
        libxml_use_internal_errors(true);
        $solicitud = self::verificarMaximoAutorizado($tipo_dte, $empresa);
        unset($solicitud['NEW']);
        $solicitud['COD_DOCTO'] = $tipo_dte;
        $solicitud['CANT_DOCTOS'] = $cantidad;
        $solicitud['ACEPTAR'] = 'Solicitar Numeración';

        $sii = new Sii($empresa);
        $certificado = $empresa->descargarCertificadoPem();
        $cookieJar = $sii->obtenerCookies($certificado[1], $certificado[2]);
        $empresa->borrarCertificado($certificado[0]);


        $client = new \GuzzleHttp\Client();
        $response = $client->post($sii->generarUrlConfirmaFolio(),
            [
                'form_params' => $solicitud,
                'headers' => [
                    'User-Agent' => Sii::USER_AGENT,
                    'Accept' => '*/*',
                    'Referer' => $sii->generarUrlConfirmaFolio(),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Connection' => 'keep-alive',
                ],
                'cookies' => $cookieJar,
                'curl' => [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_PORT => 443,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                ],
            ]);

        $content = $response->getBody()->getContents();
        $dom = new \DOMDocument();
        $dom->loadHTML($content);
        $solicitud_generar = [];

        foreach($dom->getElementsByTagName('input') as $node)
        {

            $solicitud_generar[$node->getAttribute('name')] = $node->getAttribute('value');
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->post($sii->generarUrlGeneraFolio(),
            [
                'form_params' => $solicitud_generar,
                'headers' => [
                    'User-Agent' => Sii::USER_AGENT,
                    'Accept' => '*/*',
                    'Referer' => $sii->generarUrlConfirmaFolio(),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Connection' => 'keep-alive',
                ],
                'cookies' => $cookieJar,
                'curl' => [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_PORT => 443,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                ],
            ]);

        $content = $response->getBody()->getContents();
        $dom = new \DOMDocument();
        $dom->loadHTML($content);
        $array_folios_generado = [];

        foreach($dom->getElementsByTagName('input') as $node)
        {
            $array_folios_generado[$node->getAttribute('name')] = $node->getAttribute('value');
        }

        if(count($array_folios_generado) == 7){
            $path = "FoliosSII" . $array_folios_generado['RUT_EMP'] . $array_folios_generado['COD_DOCTO'] . $array_folios_generado['FOLIO_INI']  . $solicitud_generar['ANO'] . (int)$solicitud_generar['MES'] . $solicitud_generar['DIA'] . (int)$solicitud_generar['HORA'] . $solicitud_generar['MINUTO'] . ".xml";
            Storage::put($path, '');
            $path_xml_folio = Storage::path($path);

            $client = new \GuzzleHttp\Client();
            $resource = fopen($path_xml_folio, 'w+');

            $response = $client->post($sii->generarUrlGeneraArchivo(),
                [
                    'form_params' => $array_folios_generado,
                    'headers' => [
                        'User-Agent' => Sii::USER_AGENT,
                        'Accept' => '*/*',
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    'sink' => $resource,
                    'cookies' => $cookieJar,
                    'curl' => [
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_BINARYTRANSFER => true,
                        CURLOPT_PORT => 443,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                    ],
                ]
            );

            $content = Storage::get($path);
            $size = Storage::size($path);
            $caf_data = CAF::leerCAF($path_xml_folio);

            $file = new File();
            $file = $file->uploadFileFromContent($empresa, $content, $path, 'application/xml', $size, 'caf');
            $input = [];
            $input['file_id'] = $file->id;
            $input['empresa_id'] = $empresa->id;
            $input = \App\Models\Caf::prepararInformacionParaCrear($input, $caf_data);

            $qty = \App\Models\Caf::where('empresa_id', $empresa->id)->where('completado', 0)->where('tipo_documento_id', $input['tipo_documento_id'])->get();
            if(count($qty) > 0 ){
                $input['enUso'] = 0;
            } else{
                $input['enUso'] = 1;
            }

            $caf = \App\Models\Caf::create($input);

            if ($input['enUso'] == 1) {
                \App\Models\Caf::where('id', '!=', $caf->id)->where('empresa_id', $input['empresa_id'])->where('tipo_documento_id', $caf->tipo_documento_id)->update(['enUso' => 0]);
            }

            Storage::delete($path);
            return $caf;
        }

        return false;
    }
}
