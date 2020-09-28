<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class EntidadSucursal.
 * @version January 13, 2019, 4:18 pm -03
 *
 * @property int entidad_id
 * @property int tipo
 * @property string nombre
 * @property string direccion
 * @property string direccion_xml
 * @property int region_id
 * @property int provincia_id
 * @property int comuna_id
 * @property string comuna
 * @property string ciudad
 */
class EntidadSucursal extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'entidades_sucursales';

    public $fillable = [
        'entidad_id',
        'tipo',
        'nombre',
        'direccion',
        'direccion_xml',
        'region_id',
        'provincia_id',
        'comuna_id',
        'comuna',
        'ciudad',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'entidad_id' => 'integer',
        'nombre' => 'string',
        'direccion' => 'string',
        'direccion_xml' => 'string',
        'region_id' => 'integer',
        'provincia_id' => 'integer',
        'comuna_id' => 'integer',
        'comuna' => 'string',
        'ciudad' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'entidad_id' => 'required|integer',
        'tipo' => 'integer',
        'nombre' => 'required|max:40',
        'direccion' => 'required|max:200',
        'direccion_xml' => 'string|nullable|max:60',
        'region_id' => 'required|integer',
        'provincia_id' => 'required|integer',
        'comuna_id' => 'required|integer',
        'comuna' => 'string|nullable|max:20',
        'ciudad' => 'string|nullable|max:20',
    ];
}
