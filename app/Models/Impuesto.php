<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Impuesto.
 * @version October 2, 2018, 2:51 am UTC
 *
 * @property string nombre
 * @property int porcentaje
 * @property int codigo
 */
class Impuesto extends Model
{
    use SoftDeletes;

    public $table = 'impuestos';

    public $fillable = [
        'nombre',
        'porcentaje',
        'codigo',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre' => 'string',
        'porcentaje' => 'integer',
        'codigo' => 'integer',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'nombre' => 'max:150|required',
        'porcentaje' => 'required',
        'codigo' => 'required',
    ];
}
