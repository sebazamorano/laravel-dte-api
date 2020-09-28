<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class TicketCreditNote.
 * @version April 23, 2019, 2:38 am -04
 *
 * @property int empresa_id
 * @property int documento_id
 * @property int tipoBoleta
 * @property int fechaEmision
 * @property int montoNeto
 * @property int montoExento
 * @property int iva
 * @property float tasaIva
 * @property int montoTotal
 * @property int folioBoleta
 * @property int folioNotaCredito
 */
class TicketCreditNote extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'ticket_credit_notes';

    public $fillable = [
        'empresa_id',
        'documento_id',
        'tipoBoleta',
        'fechaEmision',
        'montoNeto',
        'montoExento',
        'iva',
        'tasaIva',
        'montoTotal',
        'folioBoleta',
        'folioNotaCredito',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'documento_id' => 'integer',
        'tipoBoleta' => 'integer',
        'fechaEmision' => 'date',
        'montoNeto' => 'integer',
        'montoExento' => 'integer',
        'iva' => 'integer',
        'tasaIva' => 'float',
        'montoTotal' => 'integer',
        'folioBoleta' => 'integer',
        'folioNotaCredito' => 'integer',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'empresa_id' => 'integer|exists:empresas,id',
        'documento_id' => 'nullable|integer|exists:documentos,id',
        'fechaEmision' => 'required|date_format:Y-m-d',
        'montoNeto' => 'sometimes|numeric',
        'montoExento' => 'sometimes|required|numeric',
        'iva' => 'numeric',
        'tasaIva' => 'nullable|numeric',
        'montoTotal' => 'required|numeric',
        'folioBoleta' => 'required|integer',
        'folioNotaCredito' => 'required|integer',
    ];

    public static function buscar(Request $request)
    {
        $credit_notes = self::orderBy('id', 'DESC');

        if ($request->is('api*')) {
            $credit_notes = $credit_notes->where('empresa_id', $request->route('empresa_id'));
        } else {
            $credit_notes = $credit_notes->where('empresa_id', session('empresa_id'));
        }

        if ($request->filled('tipoBoleta') && $request->input('tipoBoleta') != 'null') {
            $credit_notes = $credit_notes->where('tipoBoleta', $request->input('tipoBoleta'));
        }

        if ($request->filled('fecha_emision_inicial')) {
            $credit_notes = $credit_notes->where('fechaEmision', '>=', $request->input('fecha_emision_inicial'));
        }

        if ($request->filled('fecha_emision_final')) {
            $credit_notes = $credit_notes->where('fechaEmision', '<=', $request->input('fecha_emision_final'));
        }

        return $credit_notes;
    }
}
