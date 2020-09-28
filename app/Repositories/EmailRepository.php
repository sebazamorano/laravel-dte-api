<?php

namespace App\Repositories;

use App\Models\Email;
use App\Repositories\Repository as BaseRepository;

/**
 * Class EmailRepository.
 * @version February 12, 2019, 6:16 pm -03
 *
 * @method Email findWithoutFail($id, $columns = ['*'])
 * @method Email find($id, $columns = ['*'])
 * @method Email first($columns = ['*'])
 */
class EmailRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'displayFrom',
        'addressFrom',
        'subject',
        'texto',
        'html',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Email::class;
    }
}
