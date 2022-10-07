<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class TipoDocumento.
 * @version October 2, 2018, 3:07 am UTC
 *
 * @property int empresa_id
 * @property string nombre
 * @property string tipoDTE
 * @property string noAplica
 * @property string nombreEnLibro
 * @property bool xml
 * @property-read Empresa empresa
 */
class TipoDocumento extends Model
{
    use SoftDeletes;

    public $table = 'tipos_documentos';

    public $fillable = [
        'empresa_id',
        'nombre',
        'tipoDTE',
        'noAplica',
        'nombreEnLibro',
        'xml',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'nombre' => 'string',
        'tipoDTE' => 'string',
        'nombreEnLibro' => 'string',
        'xml' => 'boolean',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'nombre' => 'max:100|required',
        'tipoDTE' => 'max:3|required',
        'nombreEnLibro' => 'max:100',
        'xml' => 'boolean',
    ];
}
