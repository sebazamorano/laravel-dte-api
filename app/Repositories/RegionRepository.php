<?php

namespace App\Repositories;

use App\Models\Region;
use App\Repositories\Repository as BaseRepository;

/**
 * Class RegionRepository.
 * @version October 3, 2018, 2:55 am UTC
 *
 * @method Region findWithoutFail($id, $columns = ['*'])
 * @method Region find($id, $columns = ['*'])
 * @method Region first($columns = ['*'])
 */
class RegionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pais_id',
        'nombre',
        'ISO_3166_2_CL',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Region::class;
    }
}
