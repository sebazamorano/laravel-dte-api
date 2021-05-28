<?php

namespace App\Models;

use App\Components\Pdf;
use App\Components\Sii;
use App\Components\tcpdf_barcodes_2d;
use App\File;
use Carbon\Carbon;
use Eloquent as Model;
use App\Components\Xml;
use Illuminate\Http\Request;
use App\Components\TipoArchivo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use FR3D\XmlDSig\Adapter\XmlseclibsAdapter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Exceptions\HttpResponseException;
use ADICHILE\DTEFACIL\SII\OBJECT_AND_XML\ObjectAndXML;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class Documento.
 * @version January 14, 2019, 1:22 am -03
 *
 * @property int id
 * @property int empresa_id
 * @property int sucursal_id
 * @property int tipo_documento_id
 * @property int entidad_id
 * @property int entidad_sucursal_id
 * @property int caf_id
 * @property int certificado_empresa_id
 * @property string observaciones
 * @property date fechaEmision
 * @property int folio
 * @property string folioString
 * @property int tipo_pago_id
 * @property int tipo_plazo_id
 * @property date fechaVencimiento
 * @property bigInteger neto
 * @property bigInteger exento
 * @property bigInteger iva
 * @property bigInteger total
 * @property int costo
 * @property int margen
 * @property bigInteger saldo
 * @property bool cancelado
 * @property date fechaPago
 * @property string nota
 * @property string|\Carbon\Carbon TSTED
 * @property string|\Carbon\Carbon TmstFirma
 * @property tinyInteger IO
 * @property int user_id
 * @property int estado_adicional
 * @property int estadoPago
 * @property int estado
 * @property string glosaEstadoSii
 * @property string glosaErrSii
 * @property string estadoSii
 * @property string errCode
 * @property-read DocumentoIddoc idDoc
 * @property-read Empresa empresa
 * @property DocumentoEmisor emisor
 * @property DocumentoReceptor receptor
 * @property DocumentoTotales totales
 * @property DocumentoActividadEconomica actividadesEconomicas
 * @property DocumentoDetalle detalle
 * @property DocumentoReferencia referencia
 * @property DocumentoDscRcg dscRcgGlobal
 * @property DocumentoTransporte transporte
 * @property TipoDocumento tipoDocumento
 * @property File archivos
 * @property EnvioDte envios
 * @property Caf caf
 * @property CertificadoEmpresa certificado
 */
class Documento extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'documentos';

    const FORMATO_TIMBRE = 'Y-m-d\TH:i:s';

    public $fillable = [
        'sucursal_id',
        'empresa_id',
        'tipo_documento_id',
        'entidad_id',
        'entidad_sucursal_id',
        'caf_id',
        'certificado_empresa_id',
        'observaciones',
        'fechaEmision',
        'folio',
        'folioString',
        'tipo_pago_id',
        'tipo_plazo_id',
        'fechaVencimiento',
        'neto',
        'exento',
        'iva',
        'total',
        'costo',
        'margen',
        'saldo',
        'cancelado',
        'fechaPago',
        'nota',
        'TSTED',
        'TmstFirma',
        'IO',
        'user_id',
        'estado_adicional',
        'estadoPago',
        'estado',
        'glosaEstadoSii',
        'glosaErrSii',
        'estadoSii',
        'errCode',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'tipo_documento_id' => 'integer',
        'entidad_id' => 'integer',
        'entidad_sucursal_id' => 'integer',
        'caf_id' => 'integer',
        'certificado_empresa_id' => 'integer',
        'observaciones' => 'string',
        'fechaEmision' => 'date',
        'folio' => 'integer',
        'folioString' => 'string',
        'tipo_pago_id' => 'integer',
        'tipo_plazo_id' => 'integer',
        'fechaVencimiento' => 'date',
        'costo' => 'integer',
        'margen' => 'integer',
        'cancelado' => 'boolean',
        'fechaPago' => 'date',
        'nota' => 'string',
        'user_id' => 'integer',
        'estado_adicional' => 'integer',
        'estadoPago' => 'integer',
        'estado' => 'integer',
        'neto' => 'integer',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'empresa_id' => 'required|integer',
        'tipo_documento_id' => 'required|integer',
        'entidad_id' => 'nullable|integer',
        'entidad_sucursal_id' => 'nullable|integer',
        'caf_id' => 'nullable|integer',
        'certificado_empresa_id' => 'nullable|integer',
        'observaciones' => 'nullable|string',
        'fechaEmision' => 'required|date',
        'folio' => 'nullable|integer',
        'folioString' => 'nullable|string',
        'tipo_pago_id' => 'nullable|integer',
        'tipo_plazo_id' => 'nullable|integer',
        'fechaVencimiento' => 'nullable|date',
        'neto' => 'nullable|integer',
        'exento' => 'nullable|integer',
        'iva' => 'nullable|integer',
        'total' => 'required|integer',
        'costo' => 'nullable|integer',
        'margen' => 'nullable|integer',
        'saldo' => 'nullable|integer',
        'cancelado' => 'nullable|boolean',
        'fechaPago' => 'nullable|date',
        'nota' => 'nullable|string',
        'TSTED' => 'nullable',
        'TmstFirma' => 'nullable',
        'IO' => 'integer',
        'user_id' => 'nullable|integer',
        'estado_adicional' => 'nullable|integer',
        'estadoPago' => 'nullable|integer',
        'estado' => 'integer',
    ];

    /**
     * The data of the empresa referenced to the documento.
     */
    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class);
    }

    /**
     * Get the Emisor record associated with the documento.
     */
    public function emisor()
    {
        return $this->hasOne(\App\Models\DocumentoEmisor::class, 'documento_id');
    }

    /**
     * Get the Receptor record associated with the documento.
     */
    public function receptor()
    {
        return $this->hasOne(\App\Models\DocumentoReceptor::class, 'documento_id');
    }

    /**
     * Get the IdDoc record associated with the documento.
     */
    public function idDoc()
    {
        return $this->hasOne(\App\Models\DocumentoIddoc::class);
    }

    /**
     * Get the Actecos records associated with the documento.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actividadesEconomicas()
    {
        return $this->hasMany(\App\Models\DocumentoActividadEconomica::class);
    }

    /**
     * Get the transporte record associated with the documento.
     */
    public function transporte()
    {
        return $this->hasOne(\App\Models\DocumentoTransporte::class, 'documento_id');
    }

    /**
     * Get the detalles records associated with the documento.
     */
    public function detalle()
    {
        return $this->hasMany(\App\Models\DocumentoDetalle::class);
    }

    /**
     * Get the totales record associated with the documento.
     */
    public function totales()
    {
        return $this->hasOne(\App\Models\DocumentoTotales::class, 'documento_id');
    }

    /**
     * Get the caf record associated with the documento.
     */
    public function caf()
    {
        return $this->belongsTo(\App\Models\Caf::class);
    }

    /**
     * Get the dsc rcg globals records associated with the documento.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dscRcgGlobal()
    {
        return $this->hasMany(\App\Models\DocumentoDscRcg::class);
    }

    /**
     * Get the Actecos records associated with the documento.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referencia()
    {
        return $this->hasMany(\App\Models\DocumentoReferencia::class);
    }

    public function archivos()
    {
        return $this->belongsToMany(\App\File::class);
    }

    public function envios()
    {
        return $this->belongsToMany(\App\Models\EnvioDte::class);
    }

    public function certificado()
    {
        return $this->belongsTo(\App\Models\CertificadoEmpresa::class, 'certificado_empresa_id');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(\App\Models\TipoDocumento::class);
    }

    public function generarObjectXml()
    {
        $dte = new \ADICHILE\DTEFACIL\SII\DTE\DTE();
        $type = null;
        if (in_array($this->idDoc->TipoDTE, [110, 111, 112])) {
            $type = 2;
            $dte_documento = new \ADICHILE\DTEFACIL\SII\DTE\Exportaciones();
        } elseif (in_array($this->idDoc->TipoDTE, [39, 41])) {
            $type = 3;
            $dte = new \ADICHILE\DTEFACIL\SII\BOLETAS\DTE\DTE();
            $dte->setDocumento();
            $dte_documento = $dte->getDocumento();
        } else {
            $type = 1;
            $dte->setDocumento();
            $dte_documento = $dte->getDocumento();
        }

        $dte_documento->setEncabezado();

        if (! empty($this->idDoc)) {
            $dte_documento->getEncabezado()->setIdDoc();
            foreach ($this->idDoc->getAttributes() as $index => $value) {
                $set = 'set'.$index;
                if (method_exists($dte_documento->getEncabezado()->getIdDoc(), $set) && $value !== null) {
                    $dte_documento->getEncabezado()->getIdDoc()->$set((string) $value);
                }
            }
        }

        if (! empty($this->emisor)) {
            $dte_documento->getEncabezado()->setEmisor();
            foreach ($this->emisor->getAttributes() as $index => $value) {
                $set = 'set'.$index;
                if (method_exists($dte_documento->getEncabezado()->getEmisor(), $set) && $value !== null && $value !== '') {
                    $dte_documento->getEncabezado()->getEmisor()->$set((string) $value);
                }
            }

            if ($type == 3) {
                $dte_documento->getEncabezado()->getEmisor()->setRznSocEmisor($this->emisor->RznSoc);
                $dte_documento->getEncabezado()->getEmisor()->setGiroEmisor($this->emisor->GiroEmis);
            }

            if (! empty($this->actividadesEconomicas)) {
                foreach ($this->actividadesEconomicas as $actividad) {
                    if (method_exists($dte_documento->getEncabezado()->getEmisor(), 'setActeco')) {
                        $dte_documento->getEncabezado()->getEmisor()->setActeco((string) $actividad->acteco);
                    }
                }
            }
        }

        if (! empty($this->receptor)) {
            $dte_documento->getEncabezado()->setReceptor();
            foreach ($this->receptor->getAttributes() as $index => $value) {
                $set = 'set'.$index;
                if (method_exists($dte_documento->getEncabezado()->getReceptor(), $set) && $value !== null) {
                    $dte_documento->getEncabezado()->getReceptor()->$set((string) $value);
                }
            }
        }

        if (! empty($this->transporte)) {
            if (method_exists($dte_documento->getEncabezado(), 'setTransporte')) {
                $dte_documento->getEncabezado()->setTransporte();
                if (isset($this->transporte->NombreChofer) || isset($this->transporte->RUTChofer)) {
                    $dte_documento->getEncabezado()->getTransporte()->setChofer();
                }

                foreach ($this->transporte->getAttributes() as $index => $value) {
                    $set = 'set'.$index;

                    if (method_exists($dte_documento->getEncabezado()->getTransporte()->getChofer(), $set) && $value !== null) {
                        $dte_documento->getEncabezado()->getTransporte()->getChofer()->$set((string) $value);
                    }

                    if (method_exists($dte_documento->getEncabezado()->getTransporte(), $set) && $value !== null) {
                        $dte_documento->getEncabezado()->getTransporte()->$set((string) $value);
                    }
                }
            }
        }

        if (! empty($this->totales)) {
            $dte_documento->getEncabezado()->setTotales();
            foreach ($this->totales->getAttributes() as $index => $value) {
                $set = 'set'.$index;
                if (method_exists($dte_documento->getEncabezado()->getTotales(), $set) && $value !== null) {
                    if ($index == 'MntTotal' || $index == 'MntNeto' || $index == 'MntExe') {
                        $value = (int) round($value);
                    }

                    if($this->idDoc->TipoDTE == 34 && in_array($index, ['TasaIVA', 'MntNeto', 'IVA'])){
                        continue;
                    }

                    $dte_documento->getEncabezado()->getTotales()->$set((string) $value);
                }
            }

            if($this->idDoc->TipoDTE == 41){
                $dte_documento->getEncabezado()->getTotales()->setMntNeto(null);
                $dte_documento->getEncabezado()->getTotales()->setIVA(null);
            }
        }

        if (! empty($this->detalle)) {
            foreach ($this->detalle as $detalle) {
                $detalle_xml = null;

                if ($type == 1 || $type == 2) {
                    $detalle_xml = new \ADICHILE\DTEFACIL\SII\DTE\Detalle();
                } elseif ($type == 3) {
                    $detalle_xml = new \ADICHILE\DTEFACIL\SII\BOLETAS\DTE\Detalle();
                }

                if (isset($detalle->TpoCodigo) || isset($detalle->VlrCodigo)) {
                    $detalle_xml->setCdgItem();
                }

                foreach ($detalle->getAttributes() as $index => $value) {
                    $set = 'set'.$index;

                    if (($index == 'TpoCodigo' || $index == 'VlrCodigo') && ! empty($value) && $value !== null) {
                        $detalle_xml->getCdgItem()->$set((string) $value);
                    }

                    if (method_exists($detalle_xml, $set) && $value !== null) {
                        $detalle_xml->$set((string) $value);
                    }

                    if ($index == 'IndExe' && $value == 0) {
                        $detalle_xml->setIndExe(null);
                    }
                }
                $dte_documento->setDetalle($detalle_xml);
            }
        }

        if (! empty($this->dscRcgGlobal)) {
            foreach ($this->dscRcgGlobal as $dscRcg) {
                $dscRcg_xml = null;

                if ($type == 1 || $type == 2) {
                    $dscRcg_xml = new \ADICHILE\DTEFACIL\SII\DTE\DscRcgGlobal();
                } elseif ($type == 3) {
                    $dscRcg_xml = new \ADICHILE\DTEFACIL\SII\BOLETAS\DTE\DscRcgGlobal();
                }

                foreach ($dscRcg->getAttributes() as $index => $value) {
                    $set = 'set'.$index;
                    if (method_exists($dscRcg_xml, $set) && $value !== null) {
                        $dscRcg_xml->$set((string) $value);
                    }
                }
                $dte_documento->setDscRcgGlobal($dscRcg_xml);
            }
        }

        if (! empty($this->referencia)) {
            foreach ($this->referencia as $referencia) {
                $referencia_xml = null;

                if ($type == 1 || $type == 2) {
                    $referencia_xml = new \ADICHILE\DTEFACIL\SII\DTE\Referencia();
                } elseif ($type == 3) {
                    $referencia_xml = new \ADICHILE\DTEFACIL\SII\BOLETAS\DTE\Referencia();
                }

                foreach ($referencia->getAttributes() as $index => $value) {
                    $set = 'set'.$index;
                    if (method_exists($referencia_xml, $set) && $value !== null) {
                        if($index == 'TpoDocRef' && $value == 'SET' && $type == 3){
                            continue;
                        }

                        $referencia_xml->$set((string) $value);

                        if ($index == 'CodRef' && $value == 0) {
                            $referencia_xml->$set((string) 'SET');
                        }

                    }
                }
                $dte_documento->setReferencia($referencia_xml);
            }
        }

        if (empty($this->TSTED)) {
            $TSTED = Carbon::now()->format(self::FORMATO_TIMBRE);
            $this->TSTED = $TSTED;
            $this->update();
        } else {
            $TSTED = Carbon::createFromFormat('Y-m-d H:i:s', $this->TSTED)->format(self::FORMATO_TIMBRE);
        }

        if (empty($this->TmstFirma)) {
            $TmstFirma = Carbon::now()->format(self::FORMATO_TIMBRE);
            $this->TmstFirma = $TmstFirma;
            $this->update();
        } else {
            $TmstFirma = Carbon::createFromFormat('Y-m-d H:i:s', $this->TmstFirma)->format(self::FORMATO_TIMBRE);
        }

        $dte_documento->setTmstFirma($TmstFirma);
        $dte_documento->setTED();
        $dte_documento->getTED()->setDD();
        $dte_documento->getTED()->getDD()->setRE($dte_documento->getEncabezado()->getEmisor()->getRUTEmisor());
        $dte_documento->getTED()->getDD()->setTD($dte_documento->getEncabezado()->getIdDoc()->getTipoDte());
        $dte_documento->getTED()->getDD()->setF($dte_documento->getEncabezado()->getIdDoc()->getFolio());
        $dte_documento->getTED()->getDD()->setFE($dte_documento->getEncabezado()->getIdDoc()->getFchEmis());
        $dte_documento->getTED()->getDD()->setRR($dte_documento->getEncabezado()->getReceptor()->getRUTRecep());
        $dte_documento->getTED()->getDD()->setRSR(mb_substr($dte_documento->getEncabezado()->getReceptor()->getRznSocRecep(), 0, 40));
        $dte_documento->getTED()->getDD()->setMNT($dte_documento->getEncabezado()->getTotales()->getMntTotal());

        if (count($this->detalle) > 0) {
            $dte_documento->getTED()->getDD()->setIT1(mb_substr($dte_documento->getDetalle()[0]->getNmbItem(), 0, 40));
        } else {
            $dte_documento->getTED()->getDD()->setIT1('VENTA');
        }

        $dte_documento->getTED()->getDD()->setTSTED($TSTED);

        return $dte;
    }

    public function importarCafToXml($xml)
    {
        $internal_errors = libxml_use_internal_errors(true);
        $LCAFImport = new \DOMDocument();
        $LCAFImport->formatOutput = false;
        $LCAFImport->preserveWhiteSpace = false;
        $contents = Storage::cloud()->get($this->caf->archivo->file_path);
        $marca_folio = 0;
        if (! $LCAFImport->loadXML($contents)) {
            $CAF_ENCODED = utf8_encode($contents);

            if ($LCAFImport->loadXML($CAF_ENCODED)) {
                $marca_folio = 1;
            }
        }

        $CAF = $LCAFImport->getElementsByTagName('CAF')->item(0);
        $nodecaf = $LCAFImport->getElementsByTagName('CAF')->item(0);
        $priv_key = $LCAFImport->getElementsByTagName('RSASK')->item(0)->nodeValue;

        $CAF = $LCAFImport->saveXML($CAF);

        if ($marca_folio == 1) {
            $CAF = utf8_decode($CAF);
        }

        $CAF = str_replace("'", '&apos;', $CAF);
        $data = ['xml' => $xml, 'CAF' => $CAF, 'nodecaf' => $nodecaf, 'priv_key' => $priv_key];

        return $data;
    }

    public function generarTimbre(array $data)
    {
        $DTE_TIMBRE = new \DOMDocument();
        $DTE_TIMBRE->formatOutput = false;
        $DTE_TIMBRE->preserveWhiteSpace = true;
        $DTE_TIMBRE->loadXML($data['xml']);
        $DTE_TIMBRE->encoding = 'ISO-8859-1';

        $import = $DTE_TIMBRE->importNode($data['nodecaf'], true);
        $TSTED = $DTE_TIMBRE->getElementsByTagName('TSTED')->item(0);
        $TSTED->parentNode->insertBefore($import, $TSTED);
        $RSR = utf8_decode($DTE_TIMBRE->getElementsByTagName('RSR')->item(0)->nodeValue);
        $RSR = str_replace('&', '&amp;', $RSR);
        $RSR = str_replace("'", '&apos;', $RSR);
        $RSR = str_replace('<', '&lt;', $RSR);
        $RSR = str_replace('>', '&gt;', $RSR);

        $ITEM1 = utf8_decode($DTE_TIMBRE->getElementsByTagName('IT1')->item(0)->nodeValue);
        $IT1 = str_replace('&', '&amp;', $ITEM1);
        $IT1 = str_replace("'", '&apos;', $IT1);
        $IT1 = str_replace('<', '&lt;', $IT1);
        $IT1 = str_replace('>', '&gt;', $IT1);
        $TD = $DTE_TIMBRE->getElementsByTagName('TD')->item(0)->nodeValue;
        $FOLIO = $DTE_TIMBRE->getElementsByTagName('F')->item(0)->nodeValue;
        $RUTEMIS = $DTE_TIMBRE->getElementsByTagName('RE')->item(0)->nodeValue;
        $FECHA = $DTE_TIMBRE->getElementsByTagName('FE')->item(0)->nodeValue;
        $MONTO = $DTE_TIMBRE->getElementsByTagName('MNT')->item(0)->nodeValue;
        $RUTRECE = $DTE_TIMBRE->getElementsByTagName('RR')->item(0)->nodeValue;

        if($IT1 == ''){
            $IT1_XML = "<IT1>VENTA</IT1>";
        }else{
            $IT1_XML = "<IT1>$IT1</IT1>";
        }

        $DD2 = '<DD><RE>'.$RUTEMIS.'</RE><TD>'.$TD.'</TD><F>'.$FOLIO.'</F><FE>'.$FECHA.'</FE><RR>'.$RUTRECE.
            '</RR><RSR>'.$RSR.'</RSR><MNT>'.$MONTO.'</MNT>'.$IT1_XML."{$data['CAF']}<TSTED>".
            $this->TSTED.'</TSTED></DD>';

        $FRMT = ObjectAndXML::buildSign($DD2, $data['priv_key']);
        $fragment = $DTE_TIMBRE->createDocumentFragment();
        $fragment->appendXML("<FRMT algoritmo=\"SHA1withRSA\">$FRMT</FRMT>\n");
        $TED = $DTE_TIMBRE->getElementsByTagName('TED')->item(0);
        $TED->appendChild($fragment);

        return $DTE_TIMBRE;
    }

    public function firmarXML(\DOMDocument $DTE_TIMBRE)
    {
        /* @var CertificadoEmpresa $certificado */
        $certificado = $this->certificado;
        $pfx = Storage::cloud()->get($certificado->originalFile->file_path);

        $xmlTool = new XmlseclibsAdapter();
        openssl_pkcs12_read($pfx, $key, $certificado->password);
        $xmlTool->setPrivateKey($key['pkey']);
        $xmlTool->setpublickey($key['cert']);
        $xmlTool->addTransform(XmlseclibsAdapter::ENVELOPED);

        if (in_array($this->idDoc->TipoDTE, [110, 111, 112])) {
            $xmlTool->sign($DTE_TIMBRE, 'EXPORTACION');
        } else {
            $xmlTool->sign($DTE_TIMBRE, 'DTE');
        }

        $string = $DTE_TIMBRE->saveXML();
        $string = str_replace("'", '&apos;', $string);

        return $string;
    }

    public function generarDTE()
    {
        $object_dte = $this->generarObjectXml();
        $xml = new ObjectAndXML();
        $xml->setStartElement(1);
        $id = $this->generarDteId();
        $xml->setId($id);
        $result = $xml->objToXML($object_dte);
        $caf_data = $this->importarCafToXml($result);
        $DTE_TIMBRE = $this->generarTimbre($caf_data);
        $xml_string = $this->firmarXML($DTE_TIMBRE);

        return $xml_string;
    }

    public function generarDteId()
    {
        $id = 'ID'.$this->id.'_T'.$this->idDoc->TipoDTE.'_F'.$this->idDoc->Folio;

        return $id;
    }

    public function validarXML($xml_string, &$mensaje_validacion)
    {
        $xml = new Xml();
        $dom = new \DOMDocument();
        $dom->loadXML($xml_string);
        $result = $xml->isValidSchema($dom, Xml::SCHEMA_DTE, $mensaje_validacion);

        return $result;
    }

    public static function crearDatosEmisor($input)
    {
        /* @var Empresa $empresa */
        /* @var Sucursal $sucursal */
        $empresa = Empresa::find($input['empresa_id']);
        $input['emisor']['RUTEmisor'] = $empresa->rut;
        $input['emisor']['RznSoc'] = $empresa->razonSocial;
        $input['emisor']['GiroEmis'] = $empresa->giro;

        if (! isset($input['termico'])) {
            $input['termico'] = 0;
        }

        if (isset($input['sucursal_id'])) {
            $sucursal = $empresa->sucursales()->where('id', $input['sucursal_id'])->first();

            if (empty($sucursal)) {
                throw new HttpResponseException(response()->json([
                    'message' => '422 error',
                    'errors' => ['sucursal_id' => ['La sucursal no existe.']],
                    'status_code' => 422,
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
            }
        } else {
            $sucursal = $empresa->sucursales()->where('tipo', 1)->first();
        }

        $input['sucursal_id'] = $sucursal->id;
        $input['emisor']['DirOrigen'] = $sucursal->direccionXml;
        $input['emisor']['CmnaOrigen'] = $sucursal->comuna;
        $input['emisor']['CiudadOrigen'] = $sucursal->ciudad;

        if (isset($sucursal->codigo) && $sucursal->tipo != 1) {
            $input['emisor']['CdgSIISucur'] = $sucursal->codigo;
        }

        return $input;
    }

    /*
     * Esta función se encarga de verificar si hay folios de DTE disponibles , si existe lo asocia
     */
    public function asignarFolio($nroFolio = null)
    {
        if (! in_array($this->tipo_documento_id, \App\Components\TipoDocumento::$tipos_documentos)) {
            if (empty($this->folioString)) {
                return false;
            }

            return true;
        }

        $caf = new Caf();
        $folio = $caf->solicitarFolio($this->tipo_documento_id, $this->empresa, $nroFolio);

        if (! $folio) {
            return false;
        }

        $this->folio = $nroFolio ? $nroFolio : $folio->folioUltimo;
        $this->caf_id = $folio->id;
        $this->idDoc->Folio = $nroFolio ? $nroFolio :  $folio->folioUltimo;
        $this->idDoc->update();
        $this->update();
        return true;
    }

    public function textoSucursales()
    {
        /* @var Sucursal $sucursal */
        $texto_domicilio = 'CASA MATRIZ: ';
        $texto_sucursales = '';

        if (isset($this->empresa->sucursales)) {
            foreach ($this->empresa->sucursales as $sucursal) {
                if ($sucursal->tipo == 2) {
                    $texto_sucursales .= 'SUCURSAL: '.$sucursal->direccion.', COMUNA: '.mb_strtoupper($sucursal->comuna, 'UTF-8').',  CIUDAD: '.mb_strtoupper($sucursal->ciudad, 'UTF-8').'<br/>';
                } else {
                    $texto_domicilio .= $sucursal->direccion.', COMUNA: '.mb_strtoupper($sucursal->comuna, 'UTF-8').', CIUDAD: '.mb_strtoupper($sucursal->ciudad, 'UTF-8').'<br/>';
                }
            }
        }

        $texto_domicilio .= $texto_sucursales;

        return $texto_domicilio;
    }

    public function generarArrayDataPdf($termico = 0)
    {

        $templates_dir = app_path() . '/Components/plantillas_pdf/';

        if ($termico == 1) {
            $path_xsl = $templates_dir . 'termico.xsl';
            $path_css = $templates_dir . 'termico.css';
        } else {
            $path_xsl = $templates_dir . 'carta.xsl';
            $path_css = $templates_dir . 'carta.css';
        }

        $xsl = file_get_contents($path_xsl);
        $css = file_get_contents($path_css);

        $data = [
            'numero_resolucion' => $this->idDoc->TipoDTE == 39 || $this->idDoc->TipoDTE == 41 ? $this->empresa->numeroResolucionBoleta : $this->empresa->numeroResolucion,
            'ano_resolucion' => $this->idDoc->TipoDTE == 39 || $this->idDoc->TipoDTE == 41 ? $this->empresa->fechaResolucionBoleta->format('Y') : $this->empresa->fechaResolucion->format('Y'),
            'path_xsl' => $xsl,
            'css' => $css,
            'cedible' => 0,
            'sucursales' => $this->textoSucursales(),
            'razon_social' => $this->empresa->razonSocial,
            'web_verificacion' => config('dte.document_verification_url'),
            'termico' => $termico,
            'TipoDTE' => $this->idDoc->TipoDTE,
            'observaciones' => $this->observaciones,
            'logo' => $this->empresa->logo_id ? Storage::cloud()->temporaryUrl($this->empresa->logo->file_path, Carbon::now()->addMinutes(1)): null,
        ];

        return $data;
    }

    public function subirXmlDteS3($xml_string)
    {
        $file = new File();
        $fileUpload = $file->uploadFileFromContent($this->empresa, $xml_string, $this->generarDteId().'.xml', 'application/xml', 0, 'dte');

        return $fileUpload;
    }

    public function subirPdfDteS3($pdf_string)
    {
        $file = new File();
        $fileUpload = $file->uploadFileFromContent($this->empresa, $pdf_string, $this->generarDteId().'.pdf', 'application/pdf', 0, 'pdf');

        return $fileUpload;
    }

    public function actualizarTotalesSegunRequest()
    {
        $this->neto = $this->totales->MntNeto;
        $this->iva = $this->totales->IVA;
        $this->exento = $this->totales->MntExe;
        $this->total = $this->totales->MntTotal;
        $this->update();
    }

    public function asignarCertificado()
    {
        /* @var CertificadoEmpresa $certificado */
        try {
            $certificado = $this->empresa->certificados()->where('enUso', 1)->first();
            $this->certificado_empresa_id = $certificado->id;
            $this->update();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function obtenerPdfString($xml_string = "", $termico = 1)
    {
        if($xml_string == ""){
            $xml_file = $this->archivos()->wherePivot('tipo', TipoArchivo::DTE)->latest()->first();
            $xml_string = Storage::cloud()->get($xml_file->file_path);
        }

        $data = $this->generarArrayDataPdf($termico);
        $contenido = Pdf::generarPdfDte($xml_string, $data);
        return $contenido;
    }

    public static function getDocumentsToUpdateSiiHistory($company_id)
    {
        $documentos = self::select('documentos.*')->where('documentos.empresa_id', $company_id)
            ->leftJoin('documentos_iddoc', 'documentos.id', '=', 'documentos_iddoc.documento_id')
            ->where('IO', 0)
            ->whereIn('documentos_iddoc.TipoDTE', [33, 34, 43])
            ->whereRaw('DATE(created_at) >= DATE_ADD(CURDATE(), INTERVAL -9 DAY)')
            ->get();

        return $documentos;
    }

    public static function getDocumentsCreatedInLastFiveMins($empresa_id)
    {
        $documentos = self::where('empresa_id', $empresa_id)
            ->where('IO', 0)
            ->where(function($query){
                $query->where(function($query3){
                    $query3->where('glosaEstadoSii', '<>', 'DTE Recibido')->where('glosaErrSii', '<>', 'Documento Anulado');
                })->orWhereNull('glosaEstadoSii')
                    ->orWhereRaw('DATE(created_at) >= DATE_ADD(CURDATE(), INTERVAL -6 MINUTE)');
             })
            ->where(function($query2){
                $query2->where('tipo_documento_id', '<>', 20)
                    ->where('tipo_documento_id', '<>', 21);
            })->get();

        return $documentos;
    }

    public static function getTicketsCreatedInLastFiveMins($empresa_id)
    {
        $boletas = self::where('empresa_id', $empresa_id)
            ->where('IO', 0)
            ->where(function($query){
                $query->where('glosaEstadoSii', '<>', 'DTE Recibido')->orWhereNull('glosaEstadoSii');
            })
            ->where(function($query2){
                $query2->where('tipo_documento_id', 20)
                    ->orWhere('tipo_documento_id',  21);
            })
            ->whereRaw('DATE(created_at) >= DATE_ADD(CURDATE(), INTERVAL -6 MINUTE)')
            ->where('fechaEmision', '>', '2020-12-31')->get();

        return $boletas;
    }

    public static function buscar(Request $request, $company_id = null)
    {
        $documentos = self::with(['idDoc', 'emisor', 'receptor', 'transporte', 'totales', 'detalle', 'referencia', 'actividadesEconomicas', 'dscRcgGlobal', 'tipoDocumento'])
           ->join('documentos_iddoc', 'documentos.id', '=', 'documentos_iddoc.documento_id')
           ->join('documentos_totales', 'documentos.id', '=', 'documentos_totales.documento_id')
       ->orderBy('documentos.id', 'DESC')->select('documentos.*');

        if ($company_id === null) {
            if ($request->is('api*')) {
                $company_id = $request->route('empresa_id');
            } else {
                $company_id = session('empresa_id');
            }
        }

        if ($company_id) {
            $documentos = $documentos->where('documentos.empresa_id', $company_id);
        }

        if ($request->filled('fecha_emision_inicial')) {
            $documentos = $documentos->where('documentos.fechaEmision', '>=', $request->input('fecha_emision_inicial'));
        }

        if ($request->filled('fecha_emision_final')) {
            $documentos = $documentos->where('documentos.fechaEmision', '<=', $request->input('fecha_emision_final'));
        }

        if ($request->route()->getName() == 'api.tickets.index' && ! $request->filled('tipoDte')) {
            $documentos = $documentos->where(function ($query) {
                $query->where('documentos_iddoc.TipoDTE', 39)->orWhere('documentos_iddoc.TipoDTE', 41);
            });
        }

        if($request->filled('tipo_documento_id')) {
           $documentos = $documentos->where('documentos.tipo_documento_id', $request->input('tipo_documento_id'));
        }

        if($request->filled('monto')) {
            $documentos = $documentos->where('documentos_totales.MntTotal', $request->input('monto'));
        }

        if ($request->filled('tipoDte')) {
            $documentos = $documentos->where('documentos_iddoc.TipoDTE', $request->input('tipoDte'));
        }

        if ($request->filled('folio')) {
            $documentos = $documentos->where('documentos_iddoc.Folio', $request->input('folio'));
        }

        /*
        if($request->filled('tipoDte') && $request->input('tipoDte') != 'null') {
            $respuestas = $respuestas->where('detalles_respuestas_dtes_xmls.tipoDte', $request->input('tipoDte'));
        }

        if($request->filled('folio') && $request->input('folio') != 'null'){
            $respuestas = $respuestas->where('detalles_respuestas_dtes_xmls.folio', $request->input('folio'));
        }

        if($request->filled('rut')){
            if($request->filled('contenido') && $request->input('contenido') == 2){
                $respuestas = $respuestas->where('rutEmisor', 'LIKE', '%'. $request->input('rut') .'%');
            }else{
                $respuestas = $respuestas->where('rutEmisor', $request->input('rut'));
            }
        }*/

        return $documentos;
    }

    public function crearEmailEnvioPDF($email_address)
    {
        /* @var Contribuyente $contribuyente */

        $email = new Email();
        $email->empresa_id = $this->empresa_id;
        $email->IO = 0;
        $email->bandeja = 2;
        $email->addressFrom = $this->empresa->contactoEmpresas;
        $email->displayFrom = $this->empresa->razonSocial;
        $email->fecha = Carbon::now()->format('Y-m-d H:i:s');
        $email->subject = 'ENVIO DOCUMENTO ELECTRÓNICO';
        $email->save();

        //$archivo = $this->archivos()->where('tipo', TipoArchivo::PDF_DTE)->latest()->first();
        //$email->adjuntos()->attach($archivo->id);

        $email->deliveredTo = $email_address;
        $destinatario = new EmailDestinatario();
        $destinatario->type = 1;
        $destinatario->addressTo = $email_address;
        $destinatario->displayTo = $this->receptor->RznSocRecep;
        $email->destinatarios()->save($destinatario);

        $body = '';
        $body .= '<b>SRES. '.$destinatario->displayTo.'</b>';
        $body .= '<br><br>DE ACUERDO A LA NORMATIVA LEGAL VIGENTE, ENVIAMOS DOCUMENTO TRIBUTARIO ELECTRONICO';
        $body .= '<br><br>SE ADJUNTA PDF.';
        $email->html = $body;

        $email->update();

        return $email->id;
    }

    public function obtenerNumeroResolucion()
    {
        return $this->idDoc->TipoDTE == 39 || $this->idDoc->TipoDTE == 41 ? $this->empresa->numeroResolucionBoleta : $this->empresa->numeroResolucion;
    }

    public function obtenerAnoResolucion()
    {
        return $this->idDoc->TipoDTE == 39 || $this->idDoc->TipoDTE == 41 ? $this->empresa->fechaResolucionBoleta->format('Y') : $this->empresa->fechaResolucion->format('Y');

    }

    public function obtenerPDF417($xml_string = "")
    {
        if($xml_string == ""){
            $xml_file = $this->archivos()->wherePivot('tipo', TipoArchivo::DTE)->latest()->first();
            $xml_string = Storage::cloud()->get($xml_file->file_path);
        }

        $dom = new \DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xml_string);
        $dom->encoding = 'ISO-8859-1';
        $nodes = $dom->getElementsByTagName('TED')->item(0);
        $ted = utf8_decode($dom->saveXML($nodes));
        $ted = str_replace("'", '&apos;', $ted);

        $barcode = new tcpdf_barcodes_2d($ted, 'PDF417,1,5');
        $pdf417html = $barcode->getBarcodePNG(1.0, 0.6);

        return 'data:image/png;base64,'.base64_encode($pdf417html);
    }

    public static function folioExiste($folio, $tipo_documento_id, $empresa_id){
        $consulta = Documento::where('folio', $folio)->where('tipo_documento_id', $tipo_documento_id)->where('empresa_id', $empresa_id)->where('IO', 0)->first();

        if(!empty($consulta)){
            return true;
        }

        return false;
    }

    public function consultarEstadoSii($token = false)
    {
        /* @var CertificadoEmpresa $certificado  */
        $certificado = $this->empresa->certificados()->where('enUso', 1)->first();
        $siiComponent = new Sii($this->empresa);

        if($this->glosaEstadoSii != 'DTE Recibido'){
            $documento = [
                'rut_emisor' => $this->emisor->RUTEmisor,
                'rut_receptor' => $this->receptor->RUTRecep,
                'rut_consultante' => $certificado->rut,
                'tipo' => $this->idDoc->TipoDTE,
                'folio' => (string) $this->idDoc->Folio,
                'fecha_emision' => $this->idDoc->FchEmis->format('dmY'),
                'fecha_emision_boleta' => $this->idDoc->FchEmis->format('d-m-Y'),
                'monto' => (string) $this->totales->MntTotal
            ];

            $data = $siiComponent->consultarEstadoDte($documento, $token);

            if(!in_array($this->idDoc->TipoDTE, [39,41])){
                $formato = str_replace('SII:', '', $data );
                $xml = simplexml_load_string($formato);
                $this->estadoSii = $xml->RESP_HDR->ESTADO;
                $this->glosaEstadoSii = $xml->RESP_HDR->GLOSA_ESTADO;
                $this->errCode = $xml->RESP_HDR->ERR_CODE;
                $this->glosaErrSii = $xml->RESP_HDR->GLOSA_ERR;
                $this->save();
            }else{
                if(strpos($data->descripcion, "Documento Recibido por el SII") === false){
                    $this->glosaEstadoSii = 'DTE No Recibido';
                }else{
                    $this->glosaEstadoSii = 'DTE Recibido';
                }

                $this->estadoSii = $data->codigo;
                $this->glosaErrSii = $data->descripcion;
                $this->save();
            }

            Log::info('Documento con ID: ' . $this->id . ' actualizado con estado:' . $this->glosaEstadoSii);
        }
    }

}
