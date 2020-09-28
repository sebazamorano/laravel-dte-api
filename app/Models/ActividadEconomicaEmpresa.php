<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class ActividadEconomicaEmpresa.
 * @version October 3, 2018, 2:33 am UTC
 *
 * @property int actividad_economica_id
 * @property int empresa_id
 */
class ActividadEconomicaEmpresa extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'actividades_economicas_empresas';

    public $fillable = [
        'actividad_economica_id',
        'empresa_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'actividad_economica_id' => 'integer',
        'empresa_id' => 'integer',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'actividad_economica_id' => 'required',
        'empresa_id' => 'required',
    ];
}
