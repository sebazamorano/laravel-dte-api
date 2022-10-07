<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class DocumentoActividadEconomica.
 * @version January 14, 2019, 12:11 pm -03
 *
 * @property int documento_id
 * @property int actividad_economica_id
 * @property int acteco
 * @property string descripcion
 */
class DocumentoActividadEconomica extends Model
{
    use SoftDeletes;

    public $table = 'documentos_actividades_economicas';

    public $timestamps = false;

    public $fillable = [
        'documento_id',
        'acteco',
        'descripcion',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documento_id' => 'integer',
        'acteco' => 'integer',
        'descripcion' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'documento_id' => 'required|integer',
        'acteco' => 'required|integer',
        'descripcion' => 'nullable|string|max:45',
    ];

    public function documento()
    {
        return $this->belongsTo(\App\Models\Documento::class);
    }
}
