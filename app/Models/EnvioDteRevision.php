<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class EnvioDteRevision.
 * @version February 20, 2019, 11:38 pm -03
 *
 * @property int id
 * @property int empresa_id
 * @property bigInteger envio_dte_id
 * @property int folio
 * @property tinyInteger tipoDte
 * @property string estado
 */
class EnvioDteRevision extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'envio_dte_revisiones';

    public $fillable = [
        'empresa_id',
        'envio_dte_id',
        'folio',
        'tipoDte',
        'estado',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'folio' => 'integer',
        'estado' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'empresa_id' => 'required',
        'envio_dte_id' => 'required',
        'folio' => 'integer',
        'tipoDte' => 'integer',
        'estado' => 'string|max:100',
    ];
}
