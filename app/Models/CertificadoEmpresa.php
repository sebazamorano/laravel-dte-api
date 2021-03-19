<?php

namespace App\Models;

use App\Components\Sii;
use App\File as File;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class CertificadoEmpresa.
 * @version October 3, 2018, 2:28 am UTC
 *
 * @property int id
 * @property int empresa_id
 * @property int original
 * @property int pem
 * @property string rut
 * @property string password
 * @property string|\Carbon\Carbon fechaEmision
 * @property string|\Carbon\Carbon fechaVencimiento
 * @property json subject
 * @property json issuer
 * @property int enUso
 * @property-read \App\File originalFile
 * @property-read \App\File pemFile
 */
class CertificadoEmpresa extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'certificados_empresas';

    public $fillable = [
        'empresa_id',
        'original',
        'pem',
        'rut',
        'password',
        'fechaEmision',
        'fechaVencimiento',
        'subject',
        'issuer',
        'enUso',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'original' => 'integer',
        'pem' => 'integer',
        'rut' => 'string',
        'password' => 'string',
        'enUso' => 'integer',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'original' => 'file|required',
        'pem' => 'file',
        'rut' => 'max:10|required|cl_rut',
        'password' => 'max:45|required',
    ];

    public function originalFile()
    {
        return $this->belongsTo(\App\File::class, 'original');
    }

    public function pemFile()
    {
        return $this->belongsTo(\App\File::class, 'pem');
    }

    public static function parsearCertificado($request)
    {
        $p12 = Empresa::parseCertificado($request);

        if($p12 !== false){
            return $p12;
        }

        return false;
    }

    public static function obtenerCookieJarDesdeCertificadoTemporal($request, $p12)
    {
        $siiComponent = new Sii();

        $nombre_archivo_temporal = uniqid().'.pem';
        Storage::put($nombre_archivo_temporal, $p12['cert'].$p12['pkey']);
        $cookieJar = $siiComponent->obtenerCookies(Storage::path($nombre_archivo_temporal), $request->input('password'));
        Storage::delete($nombre_archivo_temporal);

        return $cookieJar;
    }

    public static function crearCertificado($request, $empresa, $cookieJar = null)
    {
        $p12 = self::parsearCertificado($request);

        if($p12 === false){
            return false;
        }

        if($cookieJar === null){
            $cookieJar = self::obtenerCookieJarDesdeCertificadoTemporal($request, $p12);
        }

        $p12data = openssl_x509_parse($p12['cert']);
        $validFrom = strtotime(date('Y-m-d H:i:s', $p12data['validFrom_time_t']));
        $validTo = strtotime(date('Y-m-d H:i:s', $p12data['validTo_time_t']));
        $nombrePem = str_replace(['.pfx', '.p12'], '.pem', $request->file('original')->getClientOriginalName());

        $rut = $cookieJar->getCookieByName('RUT_NS')->getValue().'-'.$cookieJar->getCookieByName('DV_NS')->getValue();

        $file = new File;
        $fileUpload = $file->uploadFileFromRequest($request, 'original', 'certificados', $empresa);

        $filePem = new File;
        $fileUploadPem = $filePem->uploadFileFromContent($empresa,$p12['cert'].$p12['pkey'], $nombrePem, 'application/x-pem-file', 0, 'certificados');

        try {
            return [
                'certificate' => self::create([
                    'empresa_id' => $empresa->id,
                    'password' => $request->input('password'),
                    'original' => $fileUpload->id,
                    'pem' => $fileUploadPem->id,
                    'rut' => $rut,
                    'fechaEmision' => date(('Y-m-d H:i:s'), $validFrom),
                    'fechaVencimiento' => date(('Y-m-d H:i:s'), $validTo),
                    'subject' => json_encode($p12data['subject']),
                    'issuer' => json_encode($p12data['issuer']),
                ]),
                'cookieJar' => $cookieJar
            ];
        }catch (\Exception $e){
            return false;
                //return $e->getMessage() . $e->getCode() . $e->getLine();
        }
    }
}
