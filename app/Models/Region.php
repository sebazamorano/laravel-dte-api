<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Region.
 * @version October 3, 2018, 2:55 am UTC
 *
 * @property int pais_id
 * @property string nombre
 * @property string ISO_3166_2_CL
 */
class Region extends Model
{
    use SoftDeletes;

    public $table = 'regiones';

    public $fillable = [
        'pais_id',
        'nombre',
        'ISO_3166_2_CL',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'pais_id' => 'integer',
        'nombre' => 'string',
        'ISO_3166_2_CL' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'pais_id' => 'required',
        'nombre' => 'max:60|required',
        'ISO_3166_2_CL' => 'max:6|required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pais()
    {
        return $this->belongsTo(\App\Models\Pais::class);
    }
}
