<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class Entidad.
 * @version January 13, 2019, 3:56 pm -03
 *
 * @property int id
 * @property int empresa_id
 * @property string rut
 * @property string nombre
 * @property string razonSocial
 * @property bool personaJuridica
 * @property string telefono
 * @property string urlLogotipo
 * @property string contacto
 * @property int saldoCliente
 * @property int saldoProveedor
 * @property int creditoCliente
 * @property bool proveedor
 * @property bool cliente
 */
class Entidad extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'entidades';

    public $fillable = [
        'empresa_id',
        'rut',
        'nombre',
        'razonSocial',
        'personaJuridica',
        'telefono',
        'urlLogotipo',
        'contacto',
        'proveedor',
        'cliente',
        'saldoCliente',
        'saldoProveedor',
        'creditoCliente'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'rut' => 'string',
        'nombre' => 'string',
        'razonSocial' => 'string',
        'personaJuridica' => 'boolean',
        'telefono' => 'string',
        'urlLogotipo' => 'string',
        'contacto' => 'string',
        'proveedor' => 'boolean',
        'cliente' => 'boolean',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'empresa_id' => 'required',
        'rut' => 'required|max:11|cl_rut',
        'nombre' => 'max:255',
        'razonSocial' => 'required|max:100',
        'direccion' => 'required|max:70',
        'region_id' => 'required',
        'provincia_id' => 'required',
        'comuna_id' => 'required',
        'personaJuridica' => 'boolean',
        'telefono' => 'max:70',
        'urlLogotipo' => 'sometimes|url',
        'contacto' => 'sometimes|max:80',
        'proveedor' => 'boolean',
        'cliente' => 'boolean',
        'saldoCliente' => 'integer',
        'saldoProveedor' => 'integer',
        'creditoCliente' => 'integer',
    ];
}
