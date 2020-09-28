<?php

namespace App\Repositories;

use App\Models\EnvioDte;
use App\Repositories\Repository as BaseRepository;

/**
 * Class EnvioDteRepository.
 * @version January 27, 2019, 9:06 pm -03
 *
 * @method EnvioDte findWithoutFail($id, $columns = ['*'])
 * @method EnvioDte find($id, $columns = ['*'])
 * @method EnvioDte first($columns = ['*'])
 */
class EnvioDteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'empresa_id',
        'rutEmisor',
        'rutEnvia',
        'rutReceptor',
        'trackid',
        'setDteId',
        'rspUpload',
        'glosa',
        'estadoRecepEnv',
        'recepEnvGlosa',
        'nroDte',
        'fchResol',
        'nroResol',
        'tmstFirmaEnv',
        'digest',
        'correoRespuesta',
        'estado',
        'boleta',
        'contribuyente',
        'file_id',
        'IO',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return EnvioDte::class;
    }
}
