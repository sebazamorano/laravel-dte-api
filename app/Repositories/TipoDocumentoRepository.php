<?php

namespace App\Repositories;

use App\Models\TipoDocumento;
use App\Repositories\Repository as BaseRepository;

/**
 * Class TipoDocumentoRepository.
 * @version October 2, 2018, 3:07 am UTC
 *
 * @method TipoDocumento findWithoutFail($id, $columns = ['*'])
 * @method TipoDocumento find($id, $columns = ['*'])
 * @method TipoDocumento first($columns = ['*'])
 */
class TipoDocumentoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombreDocumento',
        'tipoDTE',
        'noAplica',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return TipoDocumento::class;
    }
}
