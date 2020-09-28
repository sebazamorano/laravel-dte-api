<?php

namespace App\Repositories;

use App\Models\DocumentoTotales;
use App\Repositories\Repository as BaseRepository;

/**
 * Class DocumentoTotalesRepository.
 * @version January 14, 2019, 2:00 am -03
 *
 * @method DocumentoTotales findWithoutFail($id, $columns = ['*'])
 * @method DocumentoTotales find($id, $columns = ['*'])
 * @method DocumentoTotales first($columns = ['*'])
 */
class DocumentoTotalesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'documento_id',
        'TpoMoneda',
        'MntNeto',
        'MntExe',
        'TasaIVA',
        'IVAProp',
        'IVATerc',
        'IVANoRet',
        'MntTotal',
        'MontoNF',
        'MontoPeriodo',
        'SaldoAnterior',
        'VlrPagar',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return DocumentoTotales::class;
    }
}
