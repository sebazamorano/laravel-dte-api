<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class Provincia.
 * @version October 3, 2018, 3:03 am UTC
 *
 * @property int region_id
 * @property string nombre
 */
class Provincia extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'provincias';

    public $fillable = [
        'region_id',
        'nombre',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'region_id' => 'integer',
        'nombre' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'region_id' => 'required|exists:region,id',
        'nombre' => 'max:60|required',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
