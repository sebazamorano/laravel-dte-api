<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class EnvioDteRevisionDetalle.
 * @version February 20, 2019, 11:49 pm -03
 *
 * @property bigInteger envio_dte_revision_id
 * @property int empresa_id
 * @property string detalle
 */
class EnvioDteRevisionDetalle extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'envio_dte_revision_detalles';

    public $fillable = [
        'envio_dte_revision_id',
        'empresa_id',
        'detalle',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'detalle' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'envio_dte_revision_id' => 'integer',
        'detalle' => 'string',
    ];
}
