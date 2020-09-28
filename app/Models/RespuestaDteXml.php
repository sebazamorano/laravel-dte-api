<?php

namespace App\Models;

use App\File;
use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use FR3D\XmlDSig\Adapter\XmlseclibsAdapter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class RespuestaDteXml.
 * @version February 24, 2019, 12:57 am -03
 *
 * @property int empresa_id
 * @property bigInteger envio_dte_id
 * @property tinyInteger tipoRespuesta
 * @property string rutResponde
 * @property string rutRecibe
 * @property string nmbContacto
 * @property string fonoContacto
 * @property string mailContacto
 * @property int nroDetalles
 * @property string|\Carbon\Carbon tmstFirmaResp
 * @property int file_id
 * @property EnvioDte envio
 * @property DetalleRespuestaDteXml detalle
 * @property Empresa empresa
 * @property File archivo
 */
class RespuestaDteXml extends Model
{
    use SoftDeletes, LadaCacheTrait;

    protected $xml;

    public $table = 'respuestas_dtes_xmls';

    public $fillable = [
        'empresa_id',
        'envio_dte_id',
        'tipoRespuesta',
        'rutResponde',
        'rutRecibe',
        'nmbContacto',
        'fonoContacto',
        'mailContacto',
        'nroDetalles',
        'tmstFirmaResp',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'rutResponde' => 'string',
        'rutRecibe' => 'string',
        'nmbContacto' => 'string',
        'fonoContacto' => 'string',
        'mailContacto' => 'string',
        'nroDetalles' => 'integer',
        'tmstFirmaResp' => 'datetime',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'tmstFirmaResp' => 'nullable|date',
    ];

    public function envio()
    {
        return $this->belongsTo(\App\Models\EnvioDte::class, 'envio_dte_id');
    }

    public function detalle()
    {
        return $this->hasMany(\App\Models\DetalleRespuestaDteXml::class);
    }

    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class);
    }

    public function archivo()
    {
        return $this->belongsTo(\App\File::class, 'file_id');
    }

    public function generarXmlRecepcionDte()
    {
        if (empty($this->tmstFirmaResp)) {
            $this->tmstFirmaResp = Carbon::now()->format('Y-m-d H:i:s');
            $this->update();
        }

        $this->xml = new \XmlWriter();
        $this->xml->openMemory();
        $this->xml->setIndent(true);

        $this->xml->startDocument('1.0', 'ISO-8859-1');
        $this->xml->startElement('RespuestaDTE');
        $this->xml->writeAttribute('version', '1.0');
        $this->xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $this->xml->writeAttribute('xmlns', 'http://www.sii.cl/SiiDte');
        $this->xml->writeAttribute('xsi:schemaLocation', 'http://www.sii.cl/SiiDte RespuestaEnvioDTE_v10.xsd');

        $this->xml->startElement('Resultado');
        $this->xml->writeAttribute('ID', 'Respuesta-'.$this->id);
        $this->xml->startElement('Caratula');
        $this->xml->writeAttribute('version', '1.0');
        $this->xml->writeElement('RutResponde', $this->rutResponde);
        $this->xml->writeElement('RutRecibe', $this->rutRecibe);
        $this->xml->writeElement('IdRespuesta', (string) $this->id);
        $this->xml->writeElement('NroDetalles', (string) $this->nroDetalles);
        $this->xml->writeElement('TmstFirmaResp', $this->tmstFirmaResp->format(Documento::FORMATO_TIMBRE));
        $this->xml->endElement();

        /* @var File $archivo */
        $archivo = $this->envio->archivos()->first();

        $this->xml->startElement('RecepcionEnvio');
        $this->xml->writeElement('NmbEnvio', $archivo->file_name);
        $this->xml->writeElement('FchRecep', (string) $this->envio->fchRecep);
        $this->xml->writeElement('CodEnvio', (string) $this->envio->id);
        $this->xml->writeElement('EnvioDTEID', $this->envio->setDteId);
        $this->xml->writeElement('RutEmisor', $this->envio->rutEmisor);
        $this->xml->writeElement('RutReceptor', $this->envio->rutReceptor);
        $this->xml->writeElement('EstadoRecepEnv', (string) $this->envio->estadoRecepEnv);
        $this->xml->writeElement('RecepEnvGlosa', (string) $this->envio->recepEnvGlosa);
        $this->xml->writeElement('NroDTE', (string) $this->envio->nroDte);

        /* @var DetalleRespuestaDteXml $detalle */
        foreach ($this->detalle as $detalle) {
            $this->xml->startElement('RecepcionDTE');
            $this->xml->writeElement('TipoDTE', (string) $detalle->tipoDte);
            $this->xml->writeElement('Folio', (string) $detalle->folio);
            $this->xml->writeElement('FchEmis', (string) $detalle->fchEmis->format('Y-m-d'));
            $this->xml->writeElement('RUTEmisor', (string) $detalle->rutEmisor);
            $this->xml->writeElement('RUTRecep', (string) $detalle->rutRecep);
            $this->xml->writeElement('MntTotal', (string) $detalle->mntTotal);
            $this->xml->writeElement('EstadoRecepDTE', (string) $detalle->estado);
            $this->xml->writeElement('RecepDTEGlosa', (string) $detalle->glosa);
            $this->xml->endElement();
        }

        $this->xml->endElement(); //FIN DE RECEPCION ENVIO
            $this->xml->endElement(); //FIN DE RESULTADO
        $this->xml->endElement(); // FIN DE RESPUESTADTE

        $this->xml->endDocument();
        $this->xml = $this->xml->outputMemory(true);
    }

    public function firmarRespuesta()
    {
        $respuesta = new \DOMDocument();
        $respuesta->formatOutput = false;
        $respuesta->preserveWhiteSpace = true;
        $respuesta->encoding = 'ISO-8859-1';
        $respuesta->loadXML($this->xml);
        $certificado = $this->empresa->certificados()->where('enUso', 1)->first();
        $pfx = Storage::cloud()->get($certificado->originalFile->file_path);

        $xmlTool = new XmlseclibsAdapter();
        openssl_pkcs12_read($pfx, $key, $certificado->password);
        $xmlTool->setPrivateKey($key['pkey']);
        $xmlTool->setpublickey($key['cert']);
        $xmlTool->addTransform(XmlseclibsAdapter::ENVELOPED);
        $xmlTool->sign($respuesta, 'RESPUESTA');

        $string = $respuesta->saveXML();

        return $string;
    }

    public function subirXmlRspuestaRecepcionS3($xml_string)
    {
        $file = new File();
        $fileUpload = $file->uploadFileFromContent($this->empresa, $xml_string, 'RSPENV_'.$this->id.'.xml', 'application/xml', 0, 'respuestas');

        if (! empty($file)) {
            $this->file_id = $fileUpload->id;
            $this->update();
        }

        return $fileUpload;
    }

    public function crearEmailRecepcionEnvio()
    {
        /* @var Contribuyente $contribuyente */

        $email = new Email();
        $email->empresa_id = $this->empresa_id;
        $email->IO = 0;
        $email->bandeja = 2;
        $email->addressFrom = $this->empresa->contactoEmpresas;
        $email->displayFrom = $this->empresa->razonSocial;
        $email->fecha = Carbon::now()->format('Y-m-d H:i:s');
        $email->subject = 'RESPUESTA RECEPCION ENVIO DTES';
        $email->save();

        $archivo = $this->archivo;
        Log::error('ARCHIVO '.json_encode($this->archivo));
        $email->adjuntos()->attach($archivo->id);

        $contribuyente = Contribuyente::where('rut', $this->rutRecibe);

        if (! empty($contribuyente) && App::environment('production')) {
            $email->deliveredTo = $contribuyente->mail;
            $destinatario = new EmailDestinatario();
            $destinatario->type = 1;
            $destinatario->addressTo = $contribuyente->mail;
            $destinatario->displayTo = $contribuyente->razonSocial;
            $email->destinatarios()->save($destinatario);
        }

        if (App::environment('local')) {
            $email->deliveredTo = 'joaquin.gamboaf@gmail.com';
            $destinatario = new EmailDestinatario();
            $destinatario->type = 1;
            $destinatario->addressTo = 'joaquin.gamboaf@gmail.com';
            $destinatario->displayTo = 'JOAQUIN GAMBOA FIGUEROA';
            $email->destinatarios()->save($destinatario);
        }

        $body = '';
        $body .= '<b>SRES. '.$destinatario->displayTo.'</b>';
        $body .= '<br><br>ENVIAMOS RESPUESTA AL ENVIO DE DTES A NUESTRA EMPRESA : '.$this->empresa->razonSocial.' RUT '.$this->empresa->rut;
        $body .= '<br><br>SE ADJUNTA XML.';
        $email->html = $body;

        $email->update();

        return $email->id;
    }
}
