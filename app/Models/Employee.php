<?php

namespace App\Models;

use App\Models\Sucursal as Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class Employee.
 * @version May 6, 2019, 2:37 am -04
 *
 * @property int company_id
 * @property int user_id
 * @property int branch_office_id
 * @property bool admin
 */
class Employee extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'employees';

    public $fillable = [
        'company_id',
        'user_id',
        'branch_office_id',
        'admin',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'company_id' => 'integer',
        'user_id' => 'integer',
        'branch_office_id' => 'integer',
        'admin' => 'boolean',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [

    ];

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'branch_office_id');
    }
}
