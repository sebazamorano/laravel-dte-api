<?php

namespace App\Repositories;

use App\Models\DocumentoIddoc;
use App\Repositories\Repository as BaseRepository;

/**
 * Class DocumentoIddocRepository.
 * @version January 14, 2019, 10:44 am -03
 *
 * @method DocumentoIddoc findWithoutFail($id, $columns = ['*'])
 * @method DocumentoIddoc find($id, $columns = ['*'])
 * @method DocumentoIddoc first($columns = ['*'])
 */
class DocumentoIddocRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'documento_id',
        'TipoDTE',
        'Folio',
        'FchEmis',
        'IndNoRebaja',
        'TipoDespacho',
        'IndTraslado',
        'TpoImpresion',
        'IndServicio',
        'MntBruto',
        'TpoTranCompra',
        'TpoTranVenta',
        'FrmaPago',
        'FrmaPagoExp',
        'FchCancel',
        'MntCancel',
        'SaldoInsol',
        'PeriodoDesde',
        'PeriodoHasta',
        'MedioPago',
        'TipoCtaPago',
        'NumCtaPago',
        'BcoPago',
        'TermPagoCdg',
        'TermPagoGlosa',
        'TermPagoDias',
        'FchVence',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return DocumentoIddoc::class;
    }
}
