<?php

namespace App\Models\SII\ConsumoFolios;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FolioRange
 *
 * @property integer sii_folio_consumption_summary_id
 * @property integer tipo_documento
 * @property integer inicial
 * @property integer final
 * @property integer utilizados
 */
class FolioRange extends Model
{
    protected $table = 'sii_folios_range';

    protected $fillable = [
        'tipo_documento',
        'inicial',
        'final',
        'utilizados'
    ];
}
