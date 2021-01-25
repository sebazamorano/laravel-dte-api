<?php

namespace App\Models;

use App\Components\Sii;
use App\File;
use App\Jobs\SubirEnvioDteSii;
use Carbon\Carbon;
use Eloquent as Model;
use App\Components\TipoArchivo;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use FR3D\XmlDSig\Adapter\XmlseclibsAdapter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class EnvioDte.
 * @version January 27, 2019, 9:06 pm -03
 *
 * @property int id
 * @property int empresa_id
 * @property int certificado_empresa_id
 * @property string rutEmisor
 * @property string rutEnvia
 * @property string rutReceptor
 * @property string trackid
 * @property string setDteId
 * @property string rspUpload
 * @property string glosa
 * @property tinyInteger estadoRecepEnv
 * @property string recepEnvGlosa
 * @property int nroDte
 * @property date fchResol
 * @property int nroResol
 * @property string|\Carbon\Carbon tmstFirmaEnv
 * @property string digest
 * @property string correoRespuesta
 * @property tinyInteger estado
 * @property tinyInteger boleta
 * @property tinyInteger contribuyente
 * @property int file_id
 * @property tinyInteger IO
 * @property string|\Carbon\Carbon fchRecep
 * @property Documento documentos
 * @property CertificadoEmpresa certificado
 * @property Empresa empresa
 */
class EnvioDte extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'envios_dtes';

    public $fillable = [
        'empresa_id',
        'certificado_empresa_id',
        'rutEmisor',
        'rutEnvia',
        'rutReceptor',
        'trackid',
        'setDteId',
        'rspUpload',
        'glosa',
        'estadoRecepEnv',
        'recepEnvGlosa',
        'nroDte',
        'fchResol',
        'nroResol',
        'tmstFirmaEnv',
        'digest',
        'correoRespuesta',
        'estado',
        'boleta',
        'contribuyente',
        'file_id',
        'IO',
        'fchRecep',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'certificado_empresa_id' => 'integer',
        'rutEmisor' => 'string',
        'rutEnvia' => 'string',
        'rutReceptor' => 'string',
        'trackid' => 'string',
        'setDteId' => 'string',
        'rspUpload' => 'string',
        'glosa' => 'string',
        'recepEnvGlosa' => 'string',
        'nroDte' => 'integer',
        'fchResol' => 'date',
        'tmstFirmaEnv' => 'datetime',
        'nroResol' => 'integer',
        'digest' => 'string',
        'correoRespuesta' => 'string',
        'file_id' => 'integer',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'empresa_id' => 'nullable',
        'certificado_empresa_id' => 'nullable|integer',
        'rutEmisor' => 'cl_rut|string|max:10',
        'rutEnvia' => 'cl_rut|string|max:10',
        'rutReceptor' => 'cl_rut|string|max:10',
        'trackid' => 'nullable|string|max:20',
        'setDteId' => 'nullable|string|max:80',
        'rspUpload' => 'nullable|string|max:100',
        'glosa' => 'nullable|string|max:100',
        'estadoRecepEnv' => 'nullable|integer',
        'recepEnvGlosa' => 'nullable|string|max:255',
        'nroDte' => 'nullable|integer',
        'fchResol' => 'nullable|date',
        'nroResol' => 'nullable|integer',
        'tmstFirmaEnv' => 'nullable|date',
        'digest' => 'nullable|string|max:255',
        'correoRespuesta' => 'nullable|string|max:80',
        'estado' => 'nullable|integer',
        'boleta' => 'integer',
        'contribuyente' => 'integer',
        'file_id' => 'nullable|integer',
        'IO' => 'integer',
    ];

    public function documentos()
    {
        return $this->belongsToMany(\App\Models\Documento::class);
    }

    public function certificado()
    {
        return $this->belongsTo(\App\Models\CertificadoEmpresa::class, 'certificado_empresa_id');
    }

    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class);
    }

    /*
     * Funcion para crear envios dte emitidos
     */
    public static function empaquetarDtes($documentos, $contribuyente = 0, $boleta = 0)
    {
        /* @var Documento $documento */
        /* @var Empresa $empresa */
        /* @var CertificadoEmpresa $certificado */
        $empresa_array = [];
        foreach ($documentos as $documento) {
            array_push($empresa_array, $documento->empresa_id);
        }

        if (count(array_unique($empresa_array)) > 1) {
            return false;
        }

        $empresa = Empresa::find($empresa_array[0]);

        if (empty($empresa)) {
            return false;
        }

        $certificado = $empresa->certificados()->where('enUso', 1)->first();

        if (empty($certificado)) {
            return false;
        }

        $envio = new self();
        $envio->empresa_id = $empresa->id;
        $envio->rutEmisor = $empresa->rut;
        $envio->rutEnvia = $certificado->rut;
        $envio->certificado_empresa_id = $certificado->id;
        $envio->rutReceptor = $contribuyente == 0 ? '60803000-K' : $documentos[0]->receptor->RUTRecep;
        $envio->contribuyente = $contribuyente;
        $envio->IO = 0;
        $envio->boleta = $boleta;
        $envio->nroDte = count($documentos);
        $envio->fchResol = $boleta == 0 ? $empresa->fechaResolucion : $empresa->fechaResolucionBoleta;
        $envio->nroResol = $boleta == 0 ? $empresa->numeroResolucion : $empresa->numeroResolucionBoleta;
        $envio->save();

        $envio->setDteId = 'RUT_'.$empresa->rut.'_ENV_'.$envio->id;

        if ($contribuyente == 1) {
            $envio->setDteId .= '_EC';
        }

        $envio->update();

        foreach ($documentos as $documento) {
            $envio->documentos()->attach($documento->id);
        }

        return $envio;
    }

    public function generarXML()
    {
        /* @var Documento $documento */
        /* @var File $archivo */
        $dom = new \DOMDocument('1.0', 'ISO-8859-1');
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = true;

        $fragment = $dom->createDocumentFragment();

        if (empty($this->tmstFirmaEnv)) {
            $fechaFirma = Carbon::now()->format('Y-m-d H:i:s');
            $this->tmstFirmaEnv = $fechaFirma;
            $this->update();
        }

        $subTotalDte = '';
        $caratula = "<Caratula version=\"1.0\">\n<RutEmisor>".$this->rutEmisor."</RutEmisor>\n<RutEnvia>".$this->rutEnvia."</RutEnvia>\n";
        $caratula .= '<RutReceptor>'.$this->rutReceptor."</RutReceptor>\n<FchResol>".$this->fchResol->format('Y-m-d')."</FchResol>\n<NroResol>".$this->nroResol."</NroResol>\n";
        $caratula .= '<TmstFirmaEnv>'.$this->tmstFirmaEnv->format(Documento::FORMATO_TIMBRE)."</TmstFirmaEnv>\n";

        $documentos = $this->documentos;

        $subTotalDte_array = [];
        foreach ($documentos as $documento) {
            $subTotalDte_array[] = ['TpoDTE' => $documento->idDoc->TipoDTE];
        }

        $subTotalDte_array = array_count_values(array_column($subTotalDte_array, 'TpoDTE'));
        foreach ($subTotalDte_array as $tipo => $cantidad) {
            $subTotalDte .= "<SubTotDTE>\n<TpoDTE>".$tipo."</TpoDTE>\n<NroDTE>".$cantidad."</NroDTE>\n</SubTotDTE>\n";
        }

        $caratula .= $subTotalDte."</Caratula>\n";

        if ($this->boleta == 0) {
            $EnvioDTE = "<EnvioDTE version='1.0' xmlns='http://www.sii.cl/SiiDte' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sii.cl/SiiDte EnvioDTE_v10.xsd'>\n";
            $EnvioDTE .= '<SetDTE ID="'.$this->setDteId."\">\n".$caratula."</SetDTE>\n</EnvioDTE>";
        } else {
            $EnvioDTE = "<EnvioBOLETA version='1.0' xmlns='http://www.sii.cl/SiiDte' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sii.cl/SiiDte EnvioBOLETA_v11.xsd'>\n";
            $EnvioDTE .= '<SetDTE ID="'.$this->setDteId."\">\n".$caratula."</SetDTE>\n</EnvioBOLETA>";
        }

        $fragment->appendXML($EnvioDTE);
        $dom->appendChild($fragment);

        $SetDTE = $dom->getElementsByTagName('SetDTE')->item(0);
        foreach ($documentos as $documento) {
            $dte = new \DOMDocument();
            $dte->formatOutput = false;
            $dte->preserveWhiteSpace = true;
            $archivo = $documento->archivos()->where('tipo', TipoArchivo::DTE)->latest()->first();
            $dte->loadXML($archivo->content());
            $dte->encoding = 'ISO-8859-1';
            $NodoDTE = $dte->getElementsByTagName('DTE')->item(0);
            $importar = $dom->importNode($NodoDTE, true);
            $SetDTE->appendChild($importar);
        }

        $DTE = $dom->getElementsByTagName('DTE');
        foreach ($DTE as $DT) {
            $DT->removeAttributeNS('http://www.w3.org/2000/09/xmldsig#', 'default');
        }

        $xml_string = $this->firmarXML($dom);

        return $xml_string;
    }

    public function firmarXML(\DOMDocument $ENVIO)
    {
        /* @var CertificadoEmpresa $certificado */
        $certificado = $this->certificado;

        $xmlTool = new XmlseclibsAdapter();
        $pfx = Storage::cloud()->get($certificado->originalFile->file_path);
        openssl_pkcs12_read($pfx, $key, $certificado->password);
        $xmlTool->setPrivateKey($key['pkey']);
        $xmlTool->setpublickey($key['cert']);
        $xmlTool->addTransform(XmlseclibsAdapter::ENVELOPED);
        $xmlTool->sign($ENVIO, 'ENVIO');
        $string = $ENVIO->saveXML();

        return $string;
    }

    public function subirXmlS3($xml_string)
    {
        try {
            $file = new File();
            $fileUpload = $file->uploadFileFromContent($this->empresa, $xml_string, $this->setDteId.'.xml', 'application/xml', 0, 'envios');

            return $fileUpload;
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function archivos()
    {
        return $this->belongsToMany(\App\File::class);
    }

    public function errores()
    {
        return $this->hasMany(\App\Models\EnvioDteError::class);
    }

    public function emails()
    {
        return $this->belongsToMany(\App\Models\Email::class);
    }

    public function crearEmail()
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

        $archivo = $this->archivos()->latest()->first();
        $email->adjuntos()->attach($archivo->id);

        $contribuyente = Contribuyente::where('rut', $this->rutReceptor);

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
        $body .= '<br><br>DE ACUERDO A LA NORMATIVA LEGAL VIGENTE, ENVIAMOS DOCUMENTO TRIBUTARIO ELECTRONICO';
        $body .= '<br><br>SE ADJUNTA XML.';
        $email->html = $body;

        $email->update();

        return $email->id;
    }

    public function subirAllSii()
    {
        $siiComponent = new Sii($this->empresa);
        $data = $siiComponent->subirEnvioDteAlSii($this, $this->archivos()->first()->id);

        if($data !== false){
            $this->estado = $data['status'];
            $this->rspUpload = Sii::getRspUploadTextFromStatus($this->estado);

            if ($data['status'] == 0) {
                $this->trackid = $data['trackId'];
            }

            if ($data['status'] == 99) {
                $this->rspUpload = $data['error'];
            }

            $this->update();
        }else{
            //SubirEnvioDteSii::dispatch($documento->id);
        }
    }
}
