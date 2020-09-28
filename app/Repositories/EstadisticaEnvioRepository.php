<?php

namespace App\Repositories;

use App\Models\EstadisticaEnvio;
use App\Repositories\Repository as BaseRepository;

/**
 * Class EstadisticaEnvioRepository.
 * @version February 20, 2019, 9:10 pm -03
 *
 * @method EstadisticaEnvio findWithoutFail($id, $columns = ['*'])
 * @method EstadisticaEnvio find($id, $columns = ['*'])
 * @method EstadisticaEnvio first($columns = ['*'])
 */
class EstadisticaEnvioRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'empresa_id',
        'envio_dte_id',
        'tipoDoc',
        'informado',
        'acepta',
        'rechazo',
        'reparo',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return EstadisticaEnvio::class;
    }
}
