<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class DocumentoIddoc.
 * @version January 14, 2019, 10:44 am -03
 *
 * @property int documento_id
 * @property string TipoDTE
 * @property int Folio
 * @property date FchEmis
 * @property tinyInteger IndNoRebaja
 * @property tinyInteger TipoDespacho
 * @property tinyInteger IndTraslado
 * @property string TpoImpresion
 * @property tinyInteger IndServicio
 * @property tinyInteger MntBruto
 * @property tinyInteger TpoTranCompra
 * @property tinyInteger TpoTranVenta
 * @property tinyInteger FmaPago
 * @property string FmaPagoExp
 * @property date FchCancel
 * @property bigInteger MntCancel
 * @property bigInteger SaldoInsol
 * @property date PeriodoDesde
 * @property date PeriodoHasta
 * @property string MedioPago
 * @property string TipoCtaPago
 * @property string NumCtaPago
 * @property string BcoPago
 * @property string TermPagoCdg
 * @property string TermPagoGlosa
 * @property int TermPagoDias
 * @property date FchVence
 */
class DocumentoIddoc extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'documentos_iddoc';

    public $timestamps = false;

    public $fillable = [
        'documento_id',
        'TipoDTE',
        'Folio',
        'FchEmis',
        'IndNoRebaja',
        'TipoDespacho',
        'IndTraslado',
        'TpoImpresion',
        'IndServicio',
        'MntBruto',
        'TpoTranCompra',
        'TpoTranVenta',
        'FmaPago',
        'FmaPagoExp',
        'FchCancel',
        'MntCancel',
        'SaldoInsol',
        'PeriodoDesde',
        'PeriodoHasta',
        'MedioPago',
        'TipoCtaPago',
        'NumCtaPago',
        'BcoPago',
        'TermPagoCdg',
        'TermPagoGlosa',
        'TermPagoDias',
        'FchVence',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documento_id' => 'integer',
        'TipoDTE' => 'string',
        'Folio' => 'integer',
        'FchEmis' => 'date',
        'TpoImpresion' => 'string',
        'FmaPagoExp' => 'string',
        'FchCancel' => 'date',
        'PeriodoDesde' => 'date',
        'PeriodoHasta' => 'date',
        'MedioPago' => 'string',
        'TipoCtaPago' => 'string',
        'NumCtaPago' => 'string',
        'BcoPago' => 'string',
        'TermPagoCdg' => 'string',
        'TermPagoGlosa' => 'string',
        'TermPagoDias' => 'integer',
        'FchVence' => 'date',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'documento_id' => 'required|integer',
        'TipoDTE' => 'nullable|string|max:3',
        'Folio' => 'nullable|integer',
        'FchEmis' => 'required',
        'IndNoRebaja' => 'nullable|integer',
        'TipoDespacho' => 'nullable|integer',
        'IndTraslado' => 'nullable|integer',
        'TpoImpresion' => 'nullable|string|max:1',
        'IndServicio' => 'nullable|integer',
        'MntBruto' => 'nullable|integer',
        'TpoTranCompra' => 'nullable|integer',
        'TpoTranVenta' => 'nullable|integer',
        'FmaPago' => 'nullable|integer',
        'FmaPagoExp' => 'nullable|string|max:2',
        'FchCancel' => 'nullable',
        'MntCancel' => 'nullable|integer',
        'SaldoInsol' => 'nullable|integer',
        'PeriodoDesde' => 'nullable',
        'PeriodoHasta' => 'nullable',
        'MedioPago' => 'nullable|string|max:2',
        'TipoCtaPago' => 'nullable|string|max:2',
        'NumCtaPago' => 'nullable|string|max:20',
        'BcoPago' => 'nullable|string|max:40',
        'TermPagoCdg' => 'nullable|string|max:4',
        'TermPagoGlosa' => 'nullable|string|max:100',
        'TermPagoDias' => 'nullable|integer',
        'FchVence' => 'nullable',
    ];
}
