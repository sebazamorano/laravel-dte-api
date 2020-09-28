<?php

namespace App\Models\SII\ConsumoFolios;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FolioConsumptionSummary
 *
 * @property integer sii_folio_consumption_id
 * @property integer tipo_documento
 * @property integer mnt_neto
 * @property integer mnt_iva
 * @property integer tasa_IVA
 * @property integer mnt_exento
 * @property integer mnt_total
 * @property integer folios_emitidos
 * @property integer folios_anulados
 * @property integer folios_utilizados
 * @property FolioRange foliosRange
 */
class FolioConsumptionSummary extends Model
{
    protected $table = 'sii_folios_consumption_summaries';


    public function foliosRange()
    {
        return $this->hasMany(FolioRange::class, 'sii_folio_consumption_summary_id');
    }
}
