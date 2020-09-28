<?php

namespace App\Repositories;

use App\Models\Impuesto;
use App\Repositories\Repository as BaseRepository;

/**
 * Class ImpuestoRepository.
 * @version October 2, 2018, 2:51 am UTC
 *
 * @method Impuesto findWithoutFail($id, $columns = ['*'])
 * @method Impuesto find($id, $columns = ['*'])
 * @method Impuesto first($columns = ['*'])
 */
class ImpuestoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre',
        'porcentaje',
        'codigo',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Impuesto::class;
    }
}
