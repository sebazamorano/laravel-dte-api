<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class DocumentoEmisor.
 * @version January 14, 2019, 1:41 am -03
 *
 * @property int documento_id
 * @property string RUTEmisor
 * @property string RznSoc
 * @property string GiroEmis
 * @property string Telefono1
 * @property string Telefono2
 * @property string CorreoEmisor
 * @property string Sucursal
 * @property int CdgSIISucur
 * @property string CodAdicSucur
 * @property string DirOrigen
 * @property string CmnaOrigen
 * @property string CiudadOrigen
 * @property string CdgVendedor
 * @property string IdAdicEmisor
 */
class DocumentoEmisor extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'documentos_emisor';

    public $fillable = [
        'documento_id',
        'RUTEmisor',
        'RznSoc',
        'GiroEmis',
        'Telefono1',
        'Telefono2',
        'CorreoEmisor',
        'Sucursal',
        'CdgSIISucur',
        'CodAdicSucur',
        'DirOrigen',
        'CmnaOrigen',
        'CiudadOrigen',
        'CdgVendedor',
        'IdAdicEmisor',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documento_id' => 'integer',
        'RUTEmisor' => 'string',
        'RznSoc' => 'string',
        'GiroEmis' => 'string',
        'Telefono1' => 'string',
        'Telefono2' => 'string',
        'CorreoEmisor' => 'string',
        'Sucursal' => 'string',
        'CdgSIISucur' => 'integer',
        'CodAdicSucur' => 'string',
        'DirOrigen' => 'string',
        'CmnaOrigen' => 'string',
        'CiudadOrigen' => 'string',
        'CdgVendedor' => 'string',
        'IdAdicEmisor' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'documento_id' => 'required|integer',
        'RUTEmisor' => 'required|cl_rut',
        'RznSoc' => 'required|string|max:100',
        'GiroEmis' => 'required|string|max:80',
        'Telefono1' => 'nullable|string|max:20',
        'Telefono2' => 'nullable|string|max:20',
        'CorreoEmisor' => 'nullable|string|max:80',
        'Sucursal' => 'nullable|string|max:20',
        'CdgSIISucur' => 'nullable|integer',
        'CodAdicSucur' => 'nullable|string|max:20',
        'DirOrigen' => 'required|string|max:70',
        'CmnaOrigen' => 'string|max:20',
        'CiudadOrigen' => 'nullable|string|max:20',
        'CdgVendedor' => 'nullable|string|max:60',
        'IdAdicEmisor' => 'nullable|string|max:20',
    ];
}
