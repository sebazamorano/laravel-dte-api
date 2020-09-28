<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class DocumentoTotales.
 * @version January 14, 2019, 2:00 am -03
 *
 * @property int documento_id
 * @property string TpoMoneda
 * @property int MntNeto
 * @property int MntExe
 * @property int TasaIVA
 * @property bigInteger IVA
 * @property bigInteger IVAProp
 * @property bigInteger IVATerc
 * @property bigInteger IVANoRet
 * @property int MntTotal
 * @property bigInteger MontoNF
 * @property bigInteger MontoPeriodo
 * @property bigInteger SaldoAnterior
 * @property bigInteger VlrPagar
 */
class DocumentoTotales extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'documentos_totales';

    public $fillable = [
        'documento_id',
        'TpoMoneda',
        'MntNeto',
        'MntExe',
        'TasaIVA',
        'IVA',
        'IVAProp',
        'IVATerc',
        'IVANoRet',
        'MntTotal',
        'MontoNF',
        'MontoPeriodo',
        'SaldoAnterior',
        'VlrPagar',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documento_id' => 'integer',
        'TpoMoneda' => 'string',
        'MntNeto' => 'integer',
        'MntExe' => 'integer',
        'TasaIVA' => 'string',
        'MntTotal' => 'integer',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'documento_id' => 'integer',
        'TpoMoneda' => 'nullable|string|max:15',
        'MntNeto' => 'nullable|numeric',
        'MntExe' => 'nullable|numeric',
        'TasaIVA' => 'nullable|numeric',
        'IVA' => 'nullable|numeric',
        'IVAProp' => 'nullable|integer',
        'IVATerc' => 'nullable|integer',
        'IVANoRet' => 'nullable|integer',
        'MntTotal' => 'numeric',
        'MontoNF' => 'nullable|integer',
        'MontoPeriodo' => 'nullable|integer',
        'SaldoAnterior' => 'nullable|integer',
        'VlrPagar' => 'nullable|integer',
    ];

    /**
     * The data of the documento referenced to the total.
     */
    public function documento()
    {
        return $this->belongsTo(\App\Models\Documento::class);
    }
}
