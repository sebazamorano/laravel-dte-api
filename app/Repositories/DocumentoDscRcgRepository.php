<?php

namespace App\Repositories;

use App\Models\DocumentoDscRcg;
use App\Repositories\Repository as BaseRepository;

/**
 * Class DocumentoDscRcgRepository.
 * @version January 14, 2019, 11:40 am -03
 *
 * @method DocumentoDscRcg findWithoutFail($id, $columns = ['*'])
 * @method DocumentoDscRcg find($id, $columns = ['*'])
 * @method DocumentoDscRcg first($columns = ['*'])
 */
class DocumentoDscRcgRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'documento_id',
        'NroLinDR',
        'TpoMov',
        'GlosaDR',
        'TpoValor',
        'ValorDR',
        'IndExe',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return DocumentoDscRcg::class;
    }
}
