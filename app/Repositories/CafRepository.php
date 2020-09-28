<?php

namespace App\Repositories;

use App\Models\Caf;
use App\Repositories\Repository as BaseRepository;

/**
 * Class CafRepository.
 * @version January 11, 2019, 12:51 pm -03
 *
 * @method Caf findWithoutFail($id, $columns = ['*'])
 * @method Caf find($id, $columns = ['*'])
 * @method Caf first($columns = ['*'])
 */
class CafRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'empresa_id',
        'tipo_documento_id',
        'file_id',
        'folioDesde',
        'folioHasta',
        'folioUltimo',
        'fa',
        'fechaVencimiento',
        'disponibles',
        'enUso',
        'completado',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Caf::class;
    }
}
