<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class ActividadEconomica.
 * @version October 2, 2018, 4:27 am UTC
 *
 * @property int codigo
 * @property string descripcion
 */
class ActividadEconomica extends Model
{
    use SoftDeletes;

    public $table = 'actividades_economicas';

    public $fillable = [
        'codigo',
        'descripcion',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'codigo' => 'integer',
        'descripcion' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'codigo' => 'required',
        'descripcion' => 'max:200|required',
    ];

    public static $labels = [
        'id' => 'Id',
        'code' => 'Codigo',
        'description' => 'Descripcion',
        'created_at' => 'Fecha de Creacion',
        'updated_at' => 'Fecha de Actualizacion',
        'deleted_at' => 'Fecha de Elminacion',
    ];
}
