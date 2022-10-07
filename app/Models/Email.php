<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Email.
 * @version February 12, 2019, 6:16 pm -03
 *
 * @property int id
 * @property int empresa_id
 * @property int user_id
 * @property string cloud_id
 * @property string displayFrom
 * @property string addressFrom
 * @property string deliveredTo
 * @property string subject
 * @property string texto
 * @property string html
 * @property int IO
 * @property int leido
 * @property int procesado
 * @property int resaltado
 * @property int bandeja
 * @property string fecha
 * @property-read Empresa empresa
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder create(array $attributes = [])
 * @method public Builder update(array $values)
 */
class Email extends Model
{
    use SoftDeletes;

    public $table = 'emails';

    public $fillable = [
        'empresa',
        'user_id',
        'cloud_id',
        'displayFrom',
        'addressFrom',
        'deliveredTo',
        'subject',
        'texto',
        'html',
        'IO',
        'leido',
        'procesado',
        'resaltado',
        'bandeja',
        'fecha',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'displayFrom' => 'string',
        'addressFrom' => 'string',
        'deliveredTo' => 'string',
        'subject' => 'string',
        'texto' => 'string',
        'html' => 'string',
        'IO' => 'integer',
        'leido' => 'integer',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'empresa_id' => 'sometimes|exists:empresas',
        'addressFrom' => 'required',
        'deliveredTo' => 'nullable|string|max:255',
        'subject' => 'required',
    ];

    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class);
    }

    /**
     * Obtiene los destinatarios correspondientes al correo.
     */
    public function destinatarios()
    {
        return $this->hasMany(\App\Models\EmailDestinatario::class);
    }

    /**
     * Obtiene los destinatarios correspondientes al correo.
     */
    public function adjuntos()
    {
        return $this->belongsToMany(\App\File::class);
    }
}
