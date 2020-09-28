<?php

namespace App\Repositories;

use App\Models\EnvioDteRevision;
use App\Repositories\Repository as BaseRepository;

/**
 * Class EnvioDteRevisionRepository.
 * @version February 20, 2019, 11:38 pm -03
 *
 * @method EnvioDteRevision findWithoutFail($id, $columns = ['*'])
 * @method EnvioDteRevision find($id, $columns = ['*'])
 * @method EnvioDteRevision first($columns = ['*'])
 */
class EnvioDteRevisionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'empresa_id',
        'envio_dte_id',
        'folio',
        'tipoDte',
        'estado',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return EnvioDteRevision::class;
    }
}
