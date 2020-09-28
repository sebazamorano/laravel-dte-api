<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class DocumentoReceptor.
 * @version January 14, 2019, 1:50 am -03
 *
 * @property int documento_id
 * @property string RUTRecep
 * @property string CdgIntRecep
 * @property string RznSocRecep
 * @property string NumId
 * @property string Nacionalidad
 * @property int TipoDocID
 * @property string IdAdicRecep
 * @property string GiroRecep
 * @property string Contacto
 * @property string CorreoRecep
 * @property string DirRecep
 * @property string CmnaRecep
 * @property string CiudadRecep
 * @property string DirPostal
 * @property string CmnaPostal
 * @property string CiudadPostal
 */
class DocumentoReceptor extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'documentos_receptor';

    public $fillable = [
        'documento_id',
        'RUTRecep',
        'CdgIntRecep',
        'RznSocRecep',
        'NumId',
        'Nacionalidad',
        'TipoDocID',
        'IdAdicRecep',
        'GiroRecep',
        'Contacto',
        'CorreoRecep',
        'DirRecep',
        'CmnaRecep',
        'CiudadRecep',
        'DirPostal',
        'CmnaPostal',
        'CiudadPostal',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documento_id' => 'integer',
        'RUTRecep' => 'string',
        'CdgIntRecep' => 'string',
        'RznSocRecep' => 'string',
        'NumId' => 'string',
        'Nacionalidad' => 'string',
        'TipoDocID' => 'integer',
        'IdAdicRecep' => 'string',
        'GiroRecep' => 'string',
        'Contacto' => 'string',
        'CorreoRecep' => 'string',
        'DirRecep' => 'string',
        'CmnaRecep' => 'string',
        'CiudadRecep' => 'string',
        'DirPostal' => 'string',
        'CmnaPostal' => 'string',
        'CiudadPostal' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'documento_id' => 'required|integer',
        'RUTRecep' => 'required|cl_rut',
        'CdgIntRecep' => 'nullable|string|max:20',
        'RznSocRecep' => 'required|string|max:100',
        'NumId' => 'nullable|string|max:20',
        'Nacionalidad' => 'nullable|string|max:3',
        'TipoDocID' => 'nullable|integer',
        'IdAdicRecep' => 'nullable|string|max:20',
        'GiroRecep' => 'required|string|max:40',
        'Contacto' => 'nullable|string|max:80',
        'CorreoRecep' => 'nullable|string|max:80',
        'DirRecep' => 'required|string|max:70',
        'CmnaRecep' => 'required|string|max:20',
        'CiudadRecep' => 'nullable|string|max:20',
        'DirPostal' => 'nullable|string|max:70',
        'CmnaPostal' => 'nullable|string|max:20',
        'CiudadPostal' => 'nullable|string|max:20',
    ];
}
