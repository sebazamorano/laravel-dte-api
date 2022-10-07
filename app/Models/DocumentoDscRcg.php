<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class DocumentoDscRcg.
 * @version January 14, 2019, 11:40 am -03
 *
 * @property int documento_id
 * @property tinyInteger NroLinDR
 * @property string TpoMov
 * @property string GlosaDR
 * @property string TpoValor
 * @property decimal ValorDR
 * @property tinyInteger IndExeDR
 */
class DocumentoDscRcg extends Model
{
    use SoftDeletes;

    public $table = 'documentos_dsc_rcg';

    public $fillable = [
        'documento_id',
        'NroLinDR',
        'TpoMov',
        'GlosaDR',
        'TpoValor',
        'ValorDR',
        'IndExeDR',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documento_id' => 'integer',
        'TpoMov' => 'string',
        'GlosaDR' => 'string',
        'TpoValor' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'documento_id' => 'required|integer',
        'NroLinDR' => 'required|integer',
        'TpoMov' => 'required|string|max:1',
        'GlosaDR' => 'required|string|max:45',
        'TpoValor' => 'nullable|string|max:1',
        'ValorDR' => 'nullable|numeric',
        'IndExeDR' => 'nullable|integer',
    ];
}
