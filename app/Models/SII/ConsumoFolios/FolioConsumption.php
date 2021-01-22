<?php

namespace App\Models\SII\ConsumoFolios;

use ADICHILE\DTEFACIL\SII\BOLETAS\REPORTE_CONSUMO_FOLIOS\ConsumoFolios;
use ADICHILE\DTEFACIL\SII\OBJECT_AND_XML\ObjectAndXML;
use App\Components\Sii;
use App\Components\TipoDocumento;
use App\File;
use App\Mail\Information;
use App\Models\CertificadoEmpresa;
use App\Models\Documento;
use App\Models\Empresa;
use Carbon\Carbon;
use FR3D\XmlDSig\Adapter\XmlseclibsAdapter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class FolioConsumption
 *
 * @property integer empresa_id
 * @property string rutEmisor
 * @property string rutEnvia
 * @property string fchResol
 * @property integer nroResol
 * @property string fchInicio
 * @property string fchFinal
 * @property integer correlativo
 * @property integer secEnvio
 * @property string tmstFirmaEnv
 * @property string dcf_id
 * @property string upload_datetime
 * @property integer status
 * @property string trackid
 * @property string rspUpload
 * @property string glosa
 *
 * @property File files
 * @property Empresa empresa
 * @property FolioConsumptionSummary foliosConsumptionSummary
 */
class FolioConsumption extends Model
{
    use SoftDeletes;
    const FORMATO_TIMBRE = 'Y-m-d\TH:i:s';
    const TIPO_XML = 6;
    protected $table = 'sii_folios_consumption';

    public function files()
    {
        //file_folio_consumption
        return $this->belongsToMany(\App\File::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function uploadSii($file = null)
    {
        $siiComponent = new Sii($this->empresa);

        $data = $siiComponent->subirRcfAlSii($this);
        $this->status = $data['status'];

        if ($data['status'] == 0) {
            $this->trackid = $data['trackId'];
        }elseif($data['status'] == 99) {
            $this->rspUpload = $data['error'];
        }else{
            $this->rspUpload = Sii::getRspUploadTextFromStatus($data['status']);
        }

        $this->upload_datetime =  Carbon::now()->format('Y-m-d H:i:s');
        $this->update();

        if ($this->status == 0){
            return true;
        }else{
            return false;
        }
    }

    public function generarXml()
    {
        $consumoFolios = new ConsumoFolios();
        $consumoFolios->setDocumento();

        $documentoConsumoFolios = $consumoFolios->getDocumentoConsumoFolios();
        $documentoConsumoFolios->setCaratula();
        $caratula = $documentoConsumoFolios->getCaratula();

        foreach ($this->getAttributes() as $index => $value) {
            $upperCamelCase = ucfirst(Str::camel($index));
            $set = 'set'.$upperCamelCase;

            if (method_exists($caratula, $set) && $value !== null) {
                $caratula->$set((string) $value);
            }
        }

        if (empty($this->tmstFirmaEnv)) {
            $tmstFirma = Carbon::now()->format(self::FORMATO_TIMBRE);
            $this->tmstFirmaEnv = $tmstFirma;
            $this->update();
        } else {
            $tmstFirma = Carbon::createFromFormat('Y-m-d H:i:s', $this->tmstFirmaEnv)->format(self::FORMATO_TIMBRE);
        }

        $documentoConsumoFolios->getCaratula()->setTmstFirmaEnv($tmstFirma);

        if(isset($this->foliosConsumptionSummary)){
            $i = 0;
            foreach($this->foliosConsumptionSummary as $resumen_folio){
                /* @var FolioConsumptionSummary $resumen_folio*/
                $documentoConsumoFolios->setResumen();
                $resumenXml = $documentoConsumoFolios->getResumen()[$i];

                foreach ($resumen_folio->getAttributes() as $index => $value) {
                    $upperCamelCase = ucfirst(Str::camel($index));
                    $set = 'set'.$upperCamelCase;

                    if (method_exists($resumenXml, $set) && $value !== null) {
                        $resumenXml->$set((string) $value);
                    }
                }

                $a = 0;
                $folioRange = $resumen_folio->foliosRange()->where('utilizados', true)->get();
                if(isset($folioRange) && count($folioRange) > 0){
                    foreach($folioRange as $rango_folios){
                        $resumenXml->setRangoUtilizados();
                        $resumenXml->getRangoUtilizados()[$a]->setInicial((string) $rango_folios->inicial);
                        $resumenXml->getRangoUtilizados()[$a]->setFinal((string) $rango_folios->final);
                        $a++;
                    }
                }

                $i++;
            }
        }

        $obj = new ObjectAndXML();
        $obj->setStartElement( self::TIPO_XML);
        $obj->setId($this->dcf_id);
        return $obj->objToXML($consumoFolios);
    }

    public function firmarXML($xml_string)
    {
        /* @var CertificadoEmpresa $certificado */

        $xml = new \DOMDocument();
        $xml->formatOutput = false;
        $xml->preserveWhiteSpace = true;
        $xml->loadXML($xml_string);
        $xml->encoding = 'ISO-8859-1';


        $certificado = $this->empresa->obtenerCertificadoEnUso();
        $pfx = Storage::cloud()->get($certificado->originalFile->file_path);

        $xmlTool = new XmlseclibsAdapter();
        $key = [];
        openssl_pkcs12_read($pfx, $key, $certificado->password);
        $xmlTool->setPrivateKey($key['pkey']);
        $xmlTool->setpublickey($key['cert']);
        $xmlTool->addTransform(XmlseclibsAdapter::ENVELOPED);
        $xmlTool->sign($xml);
        return $xml->saveXML();
    }

    public function subirXml($xml_string)
    {
        $uniqid = uniqid() . '.xml';
        Storage::put($uniqid, $xml_string);
        $size = Storage::size($uniqid);
        Storage::delete($uniqid);

        $file_name = $this->dcf_id . '.xml';

        $array = [
            'contents' => $xml_string,
            'mime_type' => 'application/xml',
            'file_size' => $size,
            'file_name' => $file_name,
            'file_path' => uniqid() . '_' . $file_name
        ];

        $file = File::store($array, $this->empresa, 'consumos_folios');
        $this->files()->attach($file->id);

        return $file;
    }

    public function foliosConsumptionSummary()
    {
        return $this->hasMany(FolioConsumptionSummary::class, 'sii_folio_consumption_id');
    }

    public static function createInfo($empresa_id, $date, $secEnvio = 1)
    {
        /* @var $model FolioConsumption*/
        /* @var $emisor Empresa*/
        /* @var $boletas Documento*/
        /* @var $boleta Documento*/

        $exist = self::where('empresa_id', $empresa_id)->where('fchInicio', $date)->where('secEnvio', $secEnvio)->first();

        if($exist !== null){
            return false;
        }

        $model = new self();
        $emisor = Empresa::find($empresa_id);

        $certificado = $emisor->obtenerCertificadoEnUso();

        if($certificado === null){
            $details = [
                'title' => '[CERTIFICADO NO CARGADO] Reporte consumo folios no pudo ser generado',
                'message' => '[' . $date . '] Reporte consumo no pudo ser generado para la empresa [' . $emisor->rut . '] ' . $emisor->razon_social
                . ' - CERTIFICADO NO SE ENCUENTRA CARGADO',
            ];
            Mail::to(config('dte.cron_mail'))->send(new Information($details));
            return false;
        }

        $model->empresa_id = $emisor->id;
        $model->rutEmisor = $emisor->rut;
        $model->rutEnvia = $certificado->rut;
        $model->fchResol = $emisor->fechaResolucionBoleta;
        $model->nroResol = $emisor->numeroResolucionBoleta;
        $model->fchInicio = $date;
        $model->fchFinal = $date;
        $model->secEnvio = $secEnvio;
        $pRutEmpresa  = substr ($model->rutEmisor, 0, -2);
        $model->dcf_id = "RCF_R_$pRutEmpresa" . "_F_$date". "_SEC_$model->secEnvio";

        $documentos = Documento
            ::select(
                'tipo_documento_id',
                'fechaEmision',
                \DB::raw('count(*) as cantidad'),
                \DB::raw('SUM(neto) as neto'),
                \DB::raw('SUM(exento) as exento'),
                \DB::raw('SUM(iva) as iva'),
                \DB::raw('SUM(total) as total')
            )
            ->where('IO', 0)
            ->where('fechaEmision', $date)
            ->where('empresa_id', $empresa_id)
            ->whereIn('tipo_documento_id', [20,21])
            ->groupBy(
                'tipo_documento_id',
                'fechaEmision'
            )
            ->get();

        if($model->save()){
            if($documentos->count() > 0){
                foreach($documentos as $resumen_documento){
                    $resumen = new FolioConsumptionSummary();
                    $resumen->sii_folio_consumption_id = $model->id;
                    $resumen->tipo_documento = TipoDocumento::getTipoDocumentoXML($resumen_documento->tipo_documento_id);
                    $resumen->mnt_neto = $resumen_documento->neto;
                    $resumen->mnt_iva = $resumen_documento->iva;
                    $resumen->mnt_exento = $resumen_documento->exento;
                    $resumen->mnt_total = $resumen_documento->total;
                    $resumen->tasa_IVA = 19;
                    $resumen->folios_emitidos = $resumen_documento->cantidad;
                    $resumen->folios_anulados = 0;
                    $resumen->folios_utilizados = $resumen_documento->cantidad;
                    $resumen->save();

                    $folios = Documento
                        ::where('IO', 0)
                        ->where('fechaEmision', $date)
                        ->where('empresa_id', $empresa_id)
                        ->where('tipo_documento_id', $resumen_documento->tipo_documento_id)
                        ->pluck('folio')
                        ->all();

                    $collection = collect($folios);
                    $sorted_folios = $collection->sort()->values()->all();

                    $range = [];
                    $stage = [];

                    foreach($sorted_folios as $i) {
                        if(count($stage) > 0 && $i != $stage[count($stage)-1]+1) {
                            if(count($stage) > 1) {
                                $range[] = $stage;
                            }
                            $stage = [];
                        }
                        $stage[] = $i;
                    }

                    if(count($stage) > 0){
                        $range[] = $stage;
                    }

                    foreach($range as $array){
                        $resumen->foliosRange()->create([
                            'inicial' => reset($array),
                            'final' => end($array),
                            'utilizados' => true
                        ]);
                    }
                }

                $model->refresh();
                return $model;
            }else{
                $resumen = new FolioConsumptionSummary();
                $resumen->sii_folio_consumption_id = $model->id;
                $resumen->tipo_documento = 39;
                $resumen->mnt_neto = 0;
                $resumen->mnt_iva = 0;
                $resumen->mnt_exento = 0;
                $resumen->mnt_total = 0;
                $resumen->tasa_IVA = 19;
                $resumen->folios_emitidos = 0;
                $resumen->folios_anulados = 0;
                $resumen->folios_utilizados = 0;
                $resumen->save();
                $model->refresh();
                return $model;
            }

        }else{
            return false;
        }
    }

    public static function saveResponse($data)
    {
        if ($data !== false){
            $glosa = $data['glosa'];
            unset($data['glosa']);
            self::where($data)->update(['glosa' => $glosa]);
        }
    }
}
