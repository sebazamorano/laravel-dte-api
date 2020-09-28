<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class Banco.
 * @version January 13, 2019, 4:53 pm -03
 *
 * @property string codigo
 * @property string nombre
 */
class Banco extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'bancos';

    public $fillable = [
        'codigo',
        'nombre',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'codigo' => 'string',
        'nombre' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'codigo' => 'required|string|max:4',
        'nombre' => 'required|string|max:40',
    ];
}
