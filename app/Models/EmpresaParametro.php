<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class EmpresaParametro.
 * @version January 6, 2019, 2:29 am -03
 *
 * @property int empresa_id
 * @property string nombre
 * @property string valor
 * @property string|\Carbon\Carbon desde
 * @property string|\Carbon\Carbon hasta
 */
class EmpresaParametro extends Model
{
    use SoftDeletes;

    public $table = 'empresas_parametros';

    public $fillable = [
        'empresa_id',
        'nombre',
        'valor',
        'desde',
        'hasta',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'nombre' => 'string',
        'valor' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'empresa_id' => 'required',
        'nombre' => 'required',
        'valor' => 'required',
    ];
}
