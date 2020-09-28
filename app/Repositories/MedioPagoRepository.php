<?php

namespace App\Repositories;

use App\Models\MedioPago;
use App\Repositories\Repository as BaseRepository;

/**
 * Class MedioPagoRepository.
 * @version October 3, 2018, 2:44 am UTC
 *
 * @method MedioPago findWithoutFail($id, $columns = ['*'])
 * @method MedioPago find($id, $columns = ['*'])
 * @method MedioPago first($columns = ['*'])
 */
class MedioPagoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre',
        'valor',
        'xml',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return MedioPago::class;
    }
}
