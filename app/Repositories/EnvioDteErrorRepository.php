<?php

namespace App\Repositories;

use App\Models\EnvioDteError;
use App\Repositories\Repository as BaseRepository;

/**
 * Class EnvioDteErrorRepository.
 * @version February 20, 2019, 9:57 pm -03
 *
 * @method EnvioDteError findWithoutFail($id, $columns = ['*'])
 * @method EnvioDteError find($id, $columns = ['*'])
 * @method EnvioDteError first($columns = ['*'])
 */
class EnvioDteErrorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'envio_dte_id',
        'texto',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return EnvioDteError::class;
    }
}
