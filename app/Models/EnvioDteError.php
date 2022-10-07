<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class EnvioDteError.
 * @version February 20, 2019, 9:57 pm -03
 *
 * @property int id
 * @property bigInteger envio_dte_id
 * @property string texto
 */
class EnvioDteError extends Model
{
    use SoftDeletes;

    public $table = 'envio_dte_errores';

    public $fillable = [
        'envio_dte_id',
        'texto',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'texto' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [

    ];
}
