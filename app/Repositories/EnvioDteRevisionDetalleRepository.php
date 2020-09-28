<?php

namespace App\Repositories;

use App\Models\EnvioDteRevisionDetalle;
use App\Repositories\Repository as BaseRepository;

/**
 * Class EnvioDteRevisionDetalleRepository.
 * @version February 20, 2019, 11:49 pm -03
 *
 * @method EnvioDteRevisionDetalle findWithoutFail($id, $columns = ['*'])
 * @method EnvioDteRevisionDetalle find($id, $columns = ['*'])
 * @method EnvioDteRevisionDetalle first($columns = ['*'])
 */
class EnvioDteRevisionDetalleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'envio_dte_revision_id',
        'empresa_id',
        'detalle',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return EnvioDteRevisionDetalle::class;
    }
}
