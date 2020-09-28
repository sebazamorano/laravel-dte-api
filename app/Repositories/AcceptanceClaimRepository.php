<?php

namespace App\Repositories;

use App\Models\SII\AcceptanceClaim;
use App\Repositories\Repository as BaseRepository;

/**
 * Class AcceptanceClaimRepository.
 * @version May 30, 2019, 3:45 am -04
 *
 * @method AcceptanceClaim findWithoutFail($id, $columns = ['*'])
 * @method AcceptanceClaim find($id, $columns = ['*'])
 * @method AcceptanceClaim first($columns = ['*'])
 */
class AcceptanceClaimRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'rut',
        'doc_type',
        'folio',
        'action',
        'response_code',
        'response_description',
        'event_date',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return AcceptanceClaim::class;
    }
}
