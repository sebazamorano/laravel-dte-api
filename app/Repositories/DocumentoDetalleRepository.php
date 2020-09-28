<?php

namespace App\Repositories;

use App\Models\DocumentoDetalle;
use App\Repositories\Repository as BaseRepository;

/**
 * Class DocumentoDetalleRepository.
 * @version January 14, 2019, 3:22 am -03
 *
 * @method DocumentoDetalle findWithoutFail($id, $columns = ['*'])
 * @method DocumentoDetalle find($id, $columns = ['*'])
 * @method DocumentoDetalle first($columns = ['*'])
 */
class DocumentoDetalleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'documento_id',
        'bodega_producto_id',
        'producto_id',
        'NroLinDet',
        'TpoCodigo',
        'VlrCodigo',
        'IVATerc',
        'NmbItem',
        'DscItem',
        'QtyItem',
        'UnmdItem',
        'PrcItem',
        'DescuentoPct',
        'DescuentoMonto',
        'RecargoPct',
        'RecargoMonto',
        'IndExe',
        'costoTotal',
        'adicional',
        'MontoItem',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return DocumentoDetalle::class;
    }
}
