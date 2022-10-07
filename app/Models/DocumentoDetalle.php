<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class DocumentoDetalle.
 * @version January 14, 2019, 3:22 am -03
 *
 * @property int documento_id
 * @property int bodega_producto_id
 * @property int producto_id
 * @property int NroLinDet
 * @property string TpoCodigo
 * @property string VlrCodigo
 * @property string NmbItem
 * @property string DscItem
 * @property float QtyItem
 * @property string UnmdItem
 * @property float PrcItem
 * @property float DescuentoPct
 * @property bigInteger DescuentoMonto
 * @property float RecargoPct
 * @property bigInteger RecargoMonto
 * @property tinyInteger IndExe
 * @property int costoTotal
 * @property int adicional
 * @property bigInteger MontoItem
 */
class DocumentoDetalle extends Model
{
    use SoftDeletes;

    public $table = 'documentos_detalles';

    public $fillable = [
        'documento_id',
        'bodega_producto_id',
        'producto_id',
        'NroLinDet',
        'TpoCodigo',
        'VlrCodigo',
        'NmbItem',
        'DscItem',
        'QtyItem',
        'UnmdItem',
        'PrcItem',
        'DescuentoPct',
        'DescuentoMonto',
        'RecargoPct',
        'RecargoMonto',
        'IndExe',
        'costoTotal',
        'adicional',
        'MontoItem',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documento_id' => 'integer',
        'bodega_producto_id' => 'integer',
        'producto_id' => 'integer',
        'NroLinDet' => 'integer',
        'TpoCodigo' => 'string',
        'VlrCodigo' => 'string',
        'NmbItem' => 'string',
        'DscItem' => 'string',
        'QtyItem' => 'float',
        'UnmdItem' => 'string',
        'PrcItem' => 'float',
        'DescuentoPct' => 'float',
        'RecargoPct' => 'float',
        'costoTotal' => 'integer',
        'adicional' => 'integer',
        'MontoItem' => 'integer',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'documento_id' => 'required|integer',
        'bodega_producto_id' => 'nullable|integer',
        'producto_id' => 'nullable|integer',
        'NroLinDet' => 'nullable|integer',
        'TpoCodigo' => 'nullable|string|max:10',
        'VlrCodigo' => 'nullable|string|max:35',
        'NmbItem' => 'required|string|max:80',
        'DscItem' => 'nullable|string|max:1000',
        'QtyItem' => 'nullable|numeric',
        'UnmdItem' => 'nullable|string|max:4',
        'PrcItem' => 'nullable|numeric',
        'DescuentoPct' => 'nullable|numeric',
        'DescuentoMonto' => 'nullable|integer',
        'RecargoPct' => 'nullable|numeric',
        'RecargoMonto' => 'nullable|integer',
        'IndExe' => 'nullable|integer|min:0|max:1',
        'costoTotal' => 'nullable|integer',
        'adicional' => 'nullable|integer',
        'MontoItem' => 'required|integer',
    ];
}
