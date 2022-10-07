<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class DocumentoAutorizado.
 * @version October 3, 2018, 3:18 am UTC
 *
 * @property int empresa_id
 * @property int tipo_documento_id
 */
class DocumentoAutorizado extends Model
{
    use SoftDeletes;

    public $table = 'documentos_autorizados';

    public $fillable = [
        'empresa_id',
        'tipo_documento_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'tipo_documento_id' => 'integer',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'empresa_id' => 'required|exists:empresas,id',
        'tipo_documento_id' => 'required|exists:tipos_documentos,id',
    ];
}
