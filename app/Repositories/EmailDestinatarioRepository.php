<?php

namespace App\Repositories;

use App\Models\EmailDestinatario;
use App\Repositories\Repository as BaseRepository;

/**
 * Class EmailDestinatarioRepository.
 * @version February 12, 2019, 6:52 pm -03
 *
 * @method EmailDestinatario findWithoutFail($id, $columns = ['*'])
 * @method EmailDestinatario find($id, $columns = ['*'])
 * @method EmailDestinatario first($columns = ['*'])
 */
class EmailDestinatarioRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'addressTo',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return EmailDestinatario::class;
    }
}
