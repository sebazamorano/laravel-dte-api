<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class Sucursal.
 * @version October 2, 2018, 4:12 am UTC
 *
 * @property int id
 * @property int empresa_id
 * @property int tipo
 * @property int codigo
 * @property string nombre
 * @property string direccion
 * @property string direccionXml
 * @property string comuna
 * @property string ciudad
 */
class Sucursal extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'sucursales';

    protected $touches = ['empresa'];

    public $fillable = [
        'empresa_id',
        'tipo',
        'codigo',
        'nombre',
        'direccion',
        'direccionXml',
        'comuna',
        'ciudad',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'tipo' => 'integer',
        'codigo' => 'integer',
        'nombre' => 'string',
        'direccion' => 'string',
        'direccionXml' => 'string',
        'comuna' => 'string',
        'ciudad' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'empresa_id' => 'required',
        'tipo' => 'required',
        'nombre' => 'max:40|required',
        'direccion' => 'max:200|required',
        'direccionXml' => 'max:60',
        'comuna' => 'max:20|required',
        'ciudad' => 'max:20|required',
    ];

    public static $labels = [
        'id' => 'ID',
        'name' => 'Nombre',
        'region_id' => 'ID de region',
        'province_id' => 'ID de provincia',
        'municipality_id' => 'ID de municipalidad',
        'region' => 'Region',
        'province' => 'Provincia',
        'municipality' => 'Comuna',
        'api_id' => 'ID de API',
        'type' => 'Tipo',
        'sii_code' => 'Codigo del Sii',
        'address' => 'Direccion',
        'address_xml' => 'Direccion Xml',
        'phone' => 'Telefono',
        'created_at' => 'Fecha de creacion',
        'updated_at' => 'Fecha de actualizacion',
        'deleted_at' => 'Fecha de eliminacion',
    ];

    /**
     * The data of the company that owns the sucursal.
     */
    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class);
    }

    public function crearDesdeDatosEmpresa(Empresa $empresa)
    {
        $sucursal = new self();
        $sucursal->empresa_id = $empresa->id;
        $sucursal->direccion = $empresa->direccion;
        $sucursal->direccionXml = substr($empresa->direccion, 0, 60);
        $sucursal->tipo = 1;
        $sucursal->nombre = 'CASA MATRIZ';
        $sucursal->comuna = substr($empresa->comuna->nombre, 0, 20);
        $sucursal->ciudad = substr($empresa->provincia->nombre, 0, 20);
        $sucursal->save();

        return self::find($sucursal->id);
    }

    /**
     * Get the branch office inventory sources.
     */
    public function inventory_sources()
    {
        return $this->hasMany(Warehouse::class, 'branch_office_id', 'id');
    }
}
