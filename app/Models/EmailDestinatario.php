<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class EmailDestinatario.
 * @version February 12, 2019, 6:52 pm -03
 *
 * @property string displayTo
 * @property string addressTo
 * @property int type
 */
class EmailDestinatario extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'emails_destinatarios';

    public $fillable = [
        'displayTo',
        'addressTo',
        'type',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'displayTo' => 'string',
        'addressTo' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'addressTo' => 'required',
    ];
}
