<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
}
