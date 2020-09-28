<?php

namespace App\Repositories;

use App\Models\Provincia;
use App\Repositories\Repository as BaseRepository;

/**
 * Class ProvinciaRepository.
 * @version October 3, 2018, 3:03 am UTC
 *
 * @method Provincia findWithoutFail($id, $columns = ['*'])
 * @method Provincia find($id, $columns = ['*'])
 * @method Provincia first($columns = ['*'])
 */
class ProvinciaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'region_id',
        'nombre',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Provincia::class;
    }
}
