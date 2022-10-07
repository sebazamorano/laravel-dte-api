<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class UnidadMedida.
 * @version January 13, 2019, 7:02 pm -03
 *
 * @property int empresa_id
 * @property string codigo
 * @property string nombre
 */
class UnidadMedida extends Model
{
    use SoftDeletes;

    public $table = 'unidades_medidas';

    public $fillable = [
        'empresa_id',
        'codigo',
        'nombre',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'codigo' => 'string',
        'nombre' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'empresa_id' => 'nullable|integer',
        'codigo' => 'required|string|max:4',
        'nombre' => 'required|string|max:45',
    ];
}
