<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class DocumentoTransporte.
 * @version January 14, 2019, 12:00 pm -03
 *
 * @property int documento_id
 * @property string Patente
 * @property string RUTTrans
 * @property string RUTChofer
 * @property string NombreChofer
 * @property string DirDest
 * @property string CmnaDest
 * @property string CiudadDest
 */
class DocumentoTransporte extends Model
{
    use SoftDeletes;

    public $table = 'documentos_transporte';

    public $fillable = [
        'documento_id',
        'Patente',
        'RUTTrans',
        'RUTChofer',
        'NombreChofer',
        'DirDest',
        'CmnaDest',
        'CiudadDest',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documento_id' => 'integer',
        'Patente' => 'string',
        'RUTTrans' => 'string',
        'RUTChofer' => 'string',
        'NombreChofer' => 'string',
        'DirDest' => 'string',
        'CmnaDest' => 'string',
        'CiudadDest' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'documento_id' => 'integer',
        'Patente' => 'nullable|string|max:8',
        'RUTTrans' => 'nullable|cl_rut',
        'RUTChofer' => 'cl_rut|if:NombreChofer',
        'NombreChofer' => 'string|max:30|if:RUTChofer',
        'DirDest' => 'nullable|string|max:70',
        'CmnaDest' => 'nullable|string|max:20',
        'CiudadDest' => 'nullable|string|max:20',
    ];
}
