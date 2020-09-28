<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class MedioPago.
 * @version October 3, 2018, 2:44 am UTC
 *
 * @property string nombre
 * @property string valor
 * @property int xml
 */
class MedioPago extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'medios_pagos';

    public $fillable = [
        'nombre',
        'valor',
        'xml',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre' => 'string',
        'valor' => 'string',
        'xml' => 'integer',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'nombre' => 'max:45|required',
        'valor' => 'max:2|required',
    ];
}
