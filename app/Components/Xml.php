<?php

namespace App\Components;

use App\File;
use App\Models\Empresa;
use Carbon\Carbon;
use App\Models\Email;
use App\Models\EnvioDte;
use App\Models\Documento;
use App\Models\EnvioDteError;
use App\Models\RespuestaDteXml;
use App\Models\EnvioDteRevision;
use App\Models\EstadisticaEnvio;
use Illuminate\Support\Facades\Log;
use App\Jobs\EnviarEnvioDteAlReceptor;
use App\Models\DetalleRespuestaDteXml;
use App\Models\EnvioDteRevisionDetalle;
use App\Jobs\GenerarXmlRespuestaRecepcion;
use FR3D\XmlDSig\Adapter\XmlseclibsAdapter;
use App\Jobs\EnviarXmlRespuestaRecepcionEnvio;

class Xml
{
    const SCHEMA_ENVIODTE = 1;

    const SCHEMA_DTE = 2;

    const SCHEMA_BOLETA = 3;

    const SCHEMA_LIBRO_COMPRA_VENTA = 4;

    const SCHEMA_LIBRO_GUIA = 5;

    const SCHEMA_LIBRO_BOLETA = 6;

    const SCHEMA_RESPUESTA_ENVIO_DTE = 7;

    const SCHEMA_ENVIO_RECIBOS = 8;

    const SCHEMA_CONSUMO_FOLIOS = 9;

    public function getPathSchema(int $tipo)
    {
        switch ($tipo) {
            case self::SCHEMA_ENVIODTE:
                return config('dte.server_provider') == 'local' ? __DIR__ .'/schemas/ENVIODTE/EnvioDTE_v10.xsd' : '/var/task/app/Components/schemas/ENVIODTE/EnvioDTE_v10.xsd';

                break;

            case self::SCHEMA_DTE:
                return config('dte.server_provider') == 'local' ? __DIR__ .'/schemas/ENVIODTE/DTE_v10.xsd' : '/var/task/app/Components/schemas/ENVIODTE/DTE_v10.xsd';

                break;

            case self::SCHEMA_BOLETA:
                return config('dte.server_provider') == 'local' ? __DIR__ .'/schemas/BOLETAS/EnvioBOLETA_v11.xsd' : '/var/task/app/Components/schemas/BOLETAS/EnvioBOLETA_v11.xsd';

                break;

            case self::SCHEMA_LIBRO_COMPRA_VENTA:
                return '/var/task/app/Components/schemas/COMPRAVENTA/LibroCV_v10.xsd';

                break;

            case self::SCHEMA_LIBRO_GUIA:
                return '/var/task/app/Components/schemas/GUIAS/LibroGuia_v10.xsd';

                break;

            case self::SCHEMA_LIBRO_BOLETA:
                return '/var/task/app/Components/schemas/BOLETA/LibroBoleta_v10.xsd';

                break;

            case self::SCHEMA_RESPUESTA_ENVIO_DTE:
                return config('dte.server_provider') == 'local' ? __DIR__ .'/schemas/RESPUESTASDTE/RespuestaEnvioDTE.xsd' : '/var/task/app/Components/schemas/RESPUESTASDTE/RespuestaEnvioDTE.xsd';

                break;

            case self::SCHEMA_ENVIO_RECIBOS:
                return config('dte.server_provider') == 'local' ? __DIR__ .'/schemas/SETRECIBO/EnvioRecibos_v10.xsd' : '/var/task/app/Components/schemas/SETRECIBO/EnvioRecibos_v10.xsd';

                break;

            case self::SCHEMA_CONSUMO_FOLIOS:
                return config('dte.server_provider') == 'local' ? __DIR__ .'/schemas/BOLETAS/ConsumoFolio_v10.xsd' : '/var/task/app/Components/schemas/BOLETAS/ConsumoFolio_v10.xsd';

            default:
                return false;
        }
    }

    public function libxml_display_error($error = '')
    {
        $return = "<br/>\n";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "<b>Warning $error->code</b>: ";

                break;
            case LIBXML_ERR_ERROR:
                $return .= "<b>Error $error->code</b>: ";

                break;
            case LIBXML_ERR_FATAL:
                $return .= "<b>Fatal Error $error->code</b>: ";

                break;
        }
        $return .= trim($error->message);

        return $return;
    }

    public function libxml_display_errors()
    {
        $mensaje = '';
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            $mensaje .= $this->libxml_display_error($error);
            //print libxml_display_error($error);
        }
        libxml_clear_errors();

        return $mensaje;
    }

    public static function getRecepEnvGlosa($EstadoRecepEnv = 99)
    {
        $RecepEnvGlosa = 'Envio Rechazado - Otros';

        if ($EstadoRecepEnv == '0') {
            $RecepEnvGlosa = 'Envio Recibido Conforme';
        } elseif ($EstadoRecepEnv == 1) {
            $RecepEnvGlosa = 'Envio Rechazado - Error de Schema';
        } elseif ($EstadoRecepEnv == 2) {
            $RecepEnvGlosa = 'Envio Rechazado - Error de Firma';
        } elseif ($EstadoRecepEnv == 3) {
            $RecepEnvGlosa = 'Envio Rechazado - RUT Receptor No Corresponde';
        } elseif ($EstadoRecepEnv == 90) {
            $RecepEnvGlosa = 'Envio Rechazado - Archivo Repetido';
        } elseif ($EstadoRecepEnv == 91) {
            $RecepEnvGlosa = 'Envio Rechazado - Archivo Ilegible';
        } elseif ($EstadoRecepEnv == 99) {
            $RecepEnvGlosa = 'Envio Rechazado - Otros';
        }

        return $RecepEnvGlosa;
    }

    public static function getRecepDTEGlosa($EstadoRecepDTE = 99)
    {
        $RecepDTEGlosa = 'DTE No Recibido - Otros';

        if ($EstadoRecepDTE == 0) {
            $RecepDTEGlosa = 'DTE Recibido OK';
        } elseif ($EstadoRecepDTE == 1) {
            $RecepDTEGlosa = 'DTE No Recibido - Error de Firma';
        } elseif ($EstadoRecepDTE == 2) {
            $RecepDTEGlosa = 'DTE No Recibido - Error en RUT Emisor';
        } elseif ($EstadoRecepDTE == 3) {
            $RecepDTEGlosa = 'DTE No Recibido - Error en RUT Receptor';
        } elseif ($EstadoRecepDTE == 4) {
            $RecepDTEGlosa = 'DTE No Recibido - DTE Repetido';
        } elseif ($EstadoRecepDTE == 99) {
            $RecepDTEGlosa = 'DTE No Recibido - Otros';
        }

        return $RecepDTEGlosa;
    }

    public function validarFirma($uri, $tipo = '', $path = '')
    {
        $doc = new \DOMDocument();
        $doc->formatOutput = false;
        $doc->preserveWhiteSpace = true;
        $doc->load($uri);

        $nodo = $doc->getElementsByTagName($tipo);
        $xmlTool = new \FR3D\XmlDSig\Adapter\XmlseclibsAdapter();

        foreach ($nodo as $validate) {
            $doc3 = new \DOMDocument();
            $doc3->loadXML($doc->saveXML($validate));
            if (true === $xmlTool->verify($doc3)) {
                echo 'nodo bien <br/>';
            }
        }
    }

    public function isValidSchema(\DOMDocument $xml, int $tipo, &$mensaje)
    {
        try {
            if ($tipo == self::SCHEMA_DTE) {
                $xml->createAttributeNS('http://www.sii.cl/SiiDte', 'xmlns');
                $xml->loadXML($xml->saveXML());
            }

            libxml_use_internal_errors(true);
            $pathSchema = $this->getPathSchema($tipo);

            if (! $xml->schemaValidate($pathSchema)) {
                $mensaje .= '<b>DOMDocument::schemaValidate() Generated Errors!</b>';
                $mensaje .= $this->libxml_display_errors();

                return false;
            }

            return true;
        } catch (\Exception $e) {
            echo $this->libxml_display_errors();
            //Log::error('Error al leer esquema ' . $e->getMessage() . $this->libxml_display_errors());
        }

        return true;
    }

    public static function parseResultadoConsumoFolios(\DOMDocument $xml)
    {
        $estado = $xml->getElementsByTagName('Estado')->item(0)->nodeValue;

        if($estado == 'CORRECTO'){
            return [
                'trackid' => $xml->getElementsByTagName('TrackId')->item(0)->nodeValue,
                'rutEmisor' => $xml->getElementsByTagName('RutEmisor')->item(0)->nodeValue,
                'rutEnvia' => $xml->getElementsByTagName('RutEnvia')->item(0)->nodeValue,
                'fchInicio' => $xml->getElementsByTagName('FchInicio')->item(0)->nodeValue,
                'fchFinal' => $xml->getElementsByTagName('FchFinal')->item(0)->nodeValue,
                'secEnvio' => $xml->getElementsByTagName('SecEnvio')->item(0)->nodeValue,
                'glosa' => $estado,
            ];
        }

        return false;
    }

    public static function procesarResultadoEnvio($xml_string, $from, Email $email = null)
    {
        /* $from 1 == imap, 2 == sns */
        $xml = new \DOMDocument('1.0');
        $xml->loadXML($xml_string);

        /* @var EnvioDte $envio */
        $identificacion = $xml->getElementsByTagName('IDENTIFICACION')->item(0);

        if ($identificacion->getElementsByTagName('RUTEMISOR')->length == 1) {
            $rut_emisor = $identificacion->getElementsByTagName('RUTEMISOR')->item(0)->nodeValue;
        }

        if ($identificacion->getElementsByTagName('RUTENVIA')->length == 1) {
            $rut_envia = $identificacion->getElementsByTagName('RUTENVIA')->item(0)->nodeValue;
        }

        if ($identificacion->getElementsByTagName('TRACKID')->length == 1) {
            $trackid = $identificacion->getElementsByTagName('TRACKID')->item(0)->nodeValue;
        }

        if ($identificacion->getElementsByTagName('ESTADO')->length == 1) {
            $estado = $identificacion->getElementsByTagName('ESTADO')->item(0)->nodeValue;
        }

        if ($estado == 'RSC - Rechazado por Error en Schema') {
            $no_valido = 1;
        } else {
            $no_valido = 0;
        }

        if($from == 1){
            $empresa = Empresa::where('rut', $rut_emisor)->first();
        }else{
            $empresa = $email->empresa;
        }

        $envio = EnvioDte::where(['rutEmisor' => $rut_emisor, 'rutEnvia' => $rut_envia, 'trackid' => $trackid, 'IO' => 0, 'empresa_id' => $empresa->id])->first();

        if (! empty($envio)) {
            $envio->glosa = $estado;
            $envio->update();

            if ($xml->getElementsByTagName('ESTADISTICA')->length > 0) {
                $estadisticas = $xml->getElementsByTagName('ESTADISTICA')->item(0);
                $subtotales = $estadisticas->getElementsByTagName('SUBTOTAL');

                foreach ($subtotales as $subtotal) {
                    $resultado_envio = new EstadisticaEnvio();
                    $resultado_envio->envio_dte_id = $envio->id;
                    $resultado_envio->empresa_id = $envio->empresa_id;

                    $tipodoc = '';
                    $informado = 0;
                    $acepta = 0;
                    $rechazo = 0;
                    $reparo = 0;

                    if ($subtotal->getElementsByTagName('TIPODOC')->length == 1) {
                        $tipodoc = $subtotal->getElementsByTagName('TIPODOC')->item(0)->nodeValue;
                    }

                    if ($subtotal->getElementsByTagName('INFORMADO')->length == 1) {
                        $informado = $subtotal->getElementsByTagName('INFORMADO')->item(0)->nodeValue;
                    }

                    if ($subtotal->getElementsByTagName('ACEPTA')->length == 1) {
                        $acepta = $subtotal->getElementsByTagName('ACEPTA')->item(0)->nodeValue;
                    }

                    if ($subtotal->getElementsByTagName('RECHAZO')->length == 1) {
                        $rechazo = $subtotal->getElementsByTagName('RECHAZO')->item(0)->nodeValue;
                    }

                    if ($subtotal->getElementsByTagName('REPARO')->length == 1) {
                        $reparo = $subtotal->getElementsByTagName('REPARO')->item(0)->nodeValue;
                    }

                    $resultado_envio->tipoDoc = $tipodoc;
                    $resultado_envio->informado = $informado;
                    $resultado_envio->acepta = $acepta;
                    $resultado_envio->rechazo = $rechazo;
                    $resultado_envio->reparo = $reparo;
                    $resultado_envio->save();
                }
            }

            if ($xml->getElementsByTagName('ERRORENVIO')->length > 0) {
                $errorenvio = $xml->getElementsByTagName('ERRORENVIO')->item(0);
                $deterrenvios = $errorenvio->getElementsByTagName('DETERRENVIO');

                foreach ($deterrenvios as $deterrenvio) {
                    $error = new EnvioDteError();
                    $error->envio_dte_id = $envio->id;
                    $error->texto = $deterrenvio->nodeValue;
                    $error->save();
                }
            }

            //novalido

            $folios_array = [];
            if ($xml->getElementsByTagName('REVISIONENVIO')->length > 0) {
                $revisiones_envios = $xml->getElementsByTagName('REVISIONENVIO')->item(0);
                $revisiones_dtes = $revisiones_envios->getElementsByTagName('REVISIONDTE');

                foreach ($revisiones_dtes as $revision_dte) {
                    $revision = new EnvioDteRevision();
                    $revision->envio_dte_id = $envio->id;
                    $revision->empresa_id = $envio->empresa_id;

                    $folio = 0;
                    $tipodte = 0;
                    $estado = '';
                    $detalle = '';

                    if ($revision_dte->getElementsByTagName('FOLIO')->length == 1) {
                        $folio = $revision_dte->getElementsByTagName('FOLIO')->item(0)->nodeValue;
                        $folio = intval($folio);
                    }

                    if ($revision_dte->getElementsByTagName('TIPODTE')->length == 1) {
                        $tipodte = $revision_dte->getElementsByTagName('TIPODTE')->item(0)->nodeValue;
                    }

                    if ($revision_dte->getElementsByTagName('ESTADO')->length == 1) {
                        $estado = $revision_dte->getElementsByTagName('ESTADO')->item(0)->nodeValue;
                    }

                    $revision->folio = $folio;
                    $revision->tipoDte = $tipodte;
                    $revision->estado = $estado;

                    $folio_array = ['Folio'=>$folio, 'TipoDTE'=>$tipodte, 'Estado'=>$estado];
                    array_push($folios_array, $folio_array);

                    $revision->save();

                    if ($revision_dte->getElementsByTagName('DETALLE')->length > 0) {
                        $detalles_dte = $revision_dte->getElementsByTagName('DETALLE');

                        foreach ($detalles_dte as $detalle_dte) {
                            $detalles_revision = new EnvioDteRevisionDetalle();
                            $detalles_revision->envio_dte_revision_id = $revision->id;
                            $detalles_revision->empresa_id = $envio->empresa_id;
                            $detalles_revision->detalle = $detalle_dte->nodeValue;
                            $detalle .= $detalle_dte->nodeValue;
                            $detalles_revision->save();
                        }
                    }

                    /* @var Documento $dte */

                    $dte = Documento::where('folio', $folio)->where('tipo_documento_id', TipoDocumento::getTipoDocumentoId($tipodte))->where('empresa_id', $envio->empresa_id)->first();

                    if (! empty($dte)) {
                        $dte->glosaEstadoSii = $estado;
                        //$dte->glosaErrSii = $detalle;
                        $dte->update();
                    }
                }
            }

            /* @var Documento $dte_enviar*/
            $arreglo_envio = [];
            foreach ($envio->documentos as $dte_enviar) {
                array_push($arreglo_envio, ['TipoDTE'=>$dte_enviar->idDoc->TipoDTE, 'Folio'=>$dte_enviar->folio, 'Marca'=>0, 'Estado'=>'', 'id'=>$dte_enviar->id]);
            }

            foreach ($arreglo_envio as &$arreglo) {
                foreach ($folios_array as $arreglo_folio) {
                    if ($arreglo['TipoDTE'] == $arreglo_folio['TipoDTE'] && $arreglo['Folio'] == $arreglo_folio['Folio']) {
                        $arreglo['Marca'] = 1;
                        $arreglo['Estado'] = $arreglo_folio['Estado'];
                    }
                }
            }

            foreach ($arreglo_envio as $arregloEnviar) {
                $dte_enviar = Documento::find($arregloEnviar['id']);

                $array_gen = [0 => $dte_enviar];

                if ($arregloEnviar['Estado'] != 'RCH - DTE Rechazado') {
                    //dispatch enviar y crear xml
                    $empaquetado = EnvioDte::empaquetarDtes($array_gen, 1, 0);
                    $xml_string = $empaquetado->generarXML();
                    $file = $empaquetado->subirXmlS3($xml_string);

                    $empaquetado->archivos()->attach($file->id);
                    $email_id = $empaquetado->crearEmail();

                    EnviarEnvioDteAlReceptor::dispatch($email_id);
                }

                $dte_enviar->glosaEstadoSii = 'DTE Recibido';
                $dte_enviar->glosaErrSii = 'Documento Recibido por el SII. Datos Coinciden con los Registrados';

                if ($arregloEnviar['Marca'] == 1) {
                    $dte_enviar->glosaEstadoSii = $arregloEnviar['Estado'];
                    $dte_enviar->glosaErrSii = 'Documento Recibido por el SII. Recibido con Reparos';
                }

                if ($arregloEnviar['Estado'] == 'RCH - DTE Rechazado') {
                    $dte_enviar->glosaEstadoSii = $arregloEnviar['Estado'];
                    $dte_enviar->glosaErrSii = 'Documento NO Recibido por el SII.';
                }

                $dte_enviar->update();
            }
        }
    }

    public static function procesarEnvioDte(Email $email, \DOMDocument $xml, File $adjunto)
    {
        $esValido = true;
        $erroresEsquema = '';
        $estadoRecepEnv = 0;
        $xmlComponent = new self();

        $schemaError = $xmlComponent->isValidSchema($xml, self::SCHEMA_ENVIODTE, $erroresEsquema);

        if (! $schemaError) {
            $esValido = false;
            $estadoRecepEnv = 1;
            Log::error('errores '.json_encode($erroresEsquema));
        }

        if ($esValido) {
            $validator = new XmlseclibsAdapter();
            $xmlToVerify = new \DOMDocument('1.0');
            $xmlToVerify->formatOutput = false;
            $xmlToVerify->loadXML($adjunto->content());

            if (! $validator->verify($xmlToVerify)) {
                $estadoRecepEnv = 2;
                $esValido = false;
            }

            $countDigestValue = $xml->getElementsByTagName('DigestValue')->length;
            $digestCount = $countDigestValue - 1;
        }

        if ($esValido) {
            $setDTE = $xml->getElementsByTagName('SetDTE')->item(0);
            $IdSetDTE = $setDTE->getAttribute('ID');
            $doc = $xml->getElementsByTagName('DTE');
            $cantidadDte = $doc->length;

            $envio = new EnvioDte();
            $envio->empresa_id = $email->empresa_id;
            $envio->nroDte = $cantidadDte;
            $envio->setDteId = $IdSetDTE;
            $envio->rutEmisor = $xml->getElementsByTagName('RutEmisor')->item(0)->nodeValue;
            $envio->rutEnvia = $xml->getElementsByTagName('RutEnvia')->item(0)->nodeValue;
            $envio->rutReceptor = $xml->getElementsByTagName('RutReceptor')->item(0)->nodeValue;
            $envio->tmstFirmaEnv = Carbon::createFromFormat('Y-m-d\TH:i:s', $xml->getElementsByTagName('TmstFirmaEnv')->item(0)->nodeValue)->toDateTimeString();
            $envio->digest = $xml->getElementsByTagName('DigestValue')->item($digestCount)->nodeValue;
            $envio->contribuyente = 1;
            $envio->correoRespuesta = $email->addressFrom;
            $envio->IO = 1;
            $envio->fchRecep = Carbon::now()->format('Y-m-d H:i:s');

            $recepcionado_anterior = EnvioDte::where('digest', $envio->digest)->where('empresa_id', $envio->empresa_id)->where('IO', 1)->first();

            if (! empty($recepcionado_anterior)) {
                $estadoRecepEnv = 90;
            }

            if ($envio->rutReceptor != $email->empresa->rut) {
                $estadoRecepEnv = 3;
            }

            $recepEnvGlosa = self::getRecepEnvGlosa($estadoRecepEnv);

            $envio->estadoRecepEnv = $estadoRecepEnv;
            $envio->recepEnvGlosa = $recepEnvGlosa;
            $envio->save();

            $file = $envio->subirXmlS3($adjunto->content());
            $envio->archivos()->attach($file->id);

            $respuesta = new RespuestaDteXml();
            $respuesta->empresa_id = $email->empresa_id;
            $respuesta->tipoRespuesta = TipoRespuesta::RESPUESTA_RECEPCION_XML;
            $respuesta->envio_dte_id = $envio->id;
            $respuesta->rutRecibe = $envio->rutEmisor;
            $respuesta->rutResponde = $envio->empresa->rut;
            $respuesta->nroDetalles = 1;
            $respuesta->save();

            foreach ($doc as $documento) {
                $erroresEsquemaDocumento = '';
                $documentoXml = new \DOMDocument('1.0');
                $documentoXml->loadXML($xml->saveXML($documento));
                $documentoXml->encoding = 'ISO-8859-1';

                $documentoXmlToVerify = new \DOMDocument('1.0');
                $documentoXmlToVerify->loadXML($xml->saveXML($documento));
                $documentoXml->encoding = 'ISO-8859-1';

                $nodoDocumento = $documentoXml->getElementsByTagName('Documento')->item(0);
                $documentoId = $nodoDocumento->getAttribute('ID');

                $recepcionDte = new DetalleRespuestaDteXml();
                $recepcionDte->respuesta_dte_xml_id = $respuesta->id;
                $recepcionDte->tipoDte = $documentoXml->getElementsByTagName('TipoDTE')->item(0)->nodeValue;
                $recepcionDte->folio = $documentoXml->getElementsByTagName('Folio')->item(0)->nodeValue;
                $recepcionDte->fchEmis = $documentoXml->getElementsByTagName('FchEmis')->item(0)->nodeValue;
                $recepcionDte->rutEmisor = $documentoXml->getElementsByTagName('RUTEmisor')->item(0)->nodeValue;
                $recepcionDte->rutRecep = $documentoXml->getElementsByTagName('RUTRecep')->item(0)->nodeValue;
                $recepcionDte->mntTotal = $documentoXml->getElementsByTagName('MntTotal')->item(0)->nodeValue;
                $recepcionDte->digestValue = $documentoXml->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                $recepcionDte->estado = 0;

                $booleanRE = DetalleRespuestaDteXml::where('digestValue', $recepcionDte->digestValue)->first();

                if (! empty($booleanRE)) {
                    $recepcionDte->estado = 4;
                }

                if ($email->empresa->rut != $recepcionDte->rutRecep) {
                    $recepcionDte->estado = 3;
                }

                $validator = new XmlseclibsAdapter();

                if (! $validator->verify($documentoXmlToVerify)) {
                    $recepcionDte->estado = 1;
                }

                $documentoNs = new \DOMDocument('1.0');
                $documentoNs->loadXML($xml->saveXML($documento));
                $documentoNs->encoding = 'ISO-8859-1';
                $documentoNs->createAttributeNS('http://www.sii.cl/SiiDte', 'xmlns');
                $documentoNs->loadXML($documentoNs->saveXML());

                $schemaErrorDte = $xmlComponent->isValidSchema($documentoNs, self::SCHEMA_DTE, $erroresEsquemaDocumento);

                if (! $schemaErrorDte) {
                    $recepcionDte->estado = 99;
                    Log::error('errores '.$erroresEsquemaDocumento);
                }

                $recepcionDte->glosa = self::getRecepDTEGlosa($recepcionDte->estado);
                $recepcionDte->save();

                if (in_array($recepcionDte->estado, [0, 1, 4])) {
                    $file = new File();
                    $nombreArchivo = 'R'.$recepcionDte->rutEmisor.'_T'.$recepcionDte->tipoDte.'_F'.$recepcionDte->folio.'.xml';
                    $fileUpload = $file->uploadFileFromContent($email->empresa, $xml->saveXML($documento), $nombreArchivo, 'application/xml', 0, 'dtes_recibidos');

                    if (! empty($fileUpload)) {
                        $recepcionDte->file_id = $fileUpload->id;
                        $recepcionDte->update();
                    }
                }
            }

            GenerarXmlRespuestaRecepcion::dispatch($respuesta->id);
        }
    }
}
