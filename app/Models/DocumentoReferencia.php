<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class DocumentoReferencia.
 * @version January 14, 2019, 11:01 am -03
 *
 * @property int documento_id
 * @property tinyInteger NroLinRef
 * @property string TpoDocRef
 * @property tinyInteger IndGlobal
 * @property string FolioRef
 * @property date FchRef
 * @property tinyInteger CodRef
 * @property string RazonRef
 */
class DocumentoReferencia extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'documentos_referencias';

    public $fillable = [
        'documento_id',
        'NroLinRef',
        'TpoDocRef',
        'IndGlobal',
        'FolioRef',
        'FchRef',
        'CodRef',
        'RazonRef',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documento_id' => 'integer',
        'TpoDocRef' => 'string',
        'FolioRef' => 'string',
        'FchRef' => 'date',
        'RazonRef' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'documento_id' => 'required|integer',
        'NroLinRef' => 'required|integer',
        'TpoDocRef' => 'required|string|max:3',
        'IndGlobal' => 'nullable|integer',
        'FolioRef' => 'required|string|max:18',
        'FchRef' => 'required|date',
        'CodRef' => 'nullable|integer',
        'RazonRef' => 'nullable|string|max:90',
    ];
}
