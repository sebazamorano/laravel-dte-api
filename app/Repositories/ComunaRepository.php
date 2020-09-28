<?php

namespace App\Repositories;

use App\Models\Comuna;
use App\Repositories\Repository as BaseRepository;

/**
 * Class ComunaRepository.
 * @version October 3, 2018, 3:07 am UTC
 *
 * @method Comuna findWithoutFail($id, $columns = ['*'])
 * @method Comuna find($id, $columns = ['*'])
 * @method Comuna first($columns = ['*'])
 */
class ComunaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'provincia_id',
        'nombre',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Comuna::class;
    }
}
