<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class EstadisticaEnvio.
 * @version February 20, 2019, 9:10 pm -03
 *
 * @property int $id
 * @property int empresa_id
 * @property bigInteger envio_dte_id
 * @property string tipoDoc
 * @property int informado
 * @property int acepta
 * @property int rechazo
 * @property int reparo
 */
class EstadisticaEnvio extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'estadisticas_envios';

    public $fillable = [
        'empresa_id',
        'envio_dte_id',
        'tipoDoc',
        'informado',
        'acepta',
        'rechazo',
        'reparo',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'tipoDoc' => 'string',
        'informado' => 'integer',
        'acepta' => 'integer',
        'rechazo' => 'integer',
        'reparo' => 'integer',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'empresa_id' => 'required',
        'tipoDoc' => 'string|max:3',
        'informado' => 'integer',
        'acepta' => 'integer',
        'rechazo' => 'integer',
        'reparo' => 'integer',
    ];
}
