<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class Comuna.
 * @version October 3, 2018, 3:07 am UTC
 *
 * @property int provincia_id
 * @property string nombre
 */
class Comuna extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'comunas';

    public $fillable = [
        'provincia_id',
        'nombre',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'provincia_id' => 'integer',
        'nombre' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'provincia_id' => 'required|exists:provincias,id',
        'nombre' => 'max:60|required',
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }
}
