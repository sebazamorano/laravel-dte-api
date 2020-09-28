<?php

namespace App\Repositories;

use App\Models\Documento;

use App\Repositories\Repository as BaseRepository;

/**
 * Class DocumentoRepository.
 * @version January 14, 2019, 1:22 am -03
 *
 * @method Documento findWithoutFail($id, $columns = ['*'])
 * @method Documento find($id, $columns = ['*'])
 * @method Documento first($columns = ['*'])
 */
class DocumentoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'empresa_id',
        'tipo_documento_id',
        'entidad_id',
        'entidad_sucursal_id',
        'caf_id',
        'certificado_empresa_id',
        'observaciones',
        'fechaEmision',
        'folio',
        'folioString',
        'tipo_pago_id',
        'tipo_plazo_id',
        'fechaVencimiento',
        'neto',
        'exento',
        'iva',
        'total',
        'costo',
        'margen',
        'saldo',
        'cancelado',
        'fechaPago',
        'nota',
        'TSTED',
        'IO',
        'user_id',
        'estado_adicional',
        'estadoPago',
        'estado',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Documento::class;
    }
}
