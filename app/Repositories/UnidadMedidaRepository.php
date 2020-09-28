<?php

namespace App\Repositories;

use App\Models\UnidadMedida;
use App\Repositories\Repository as BaseRepository;

/**
 * Class UnidadMedidaRepository.
 * @version January 13, 2019, 7:02 pm -03
 *
 * @method UnidadMedida findWithoutFail($id, $columns = ['*'])
 * @method UnidadMedida find($id, $columns = ['*'])
 * @method UnidadMedida first($columns = ['*'])
 */
class UnidadMedidaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'empresa_id',
        'codigo',
        'nombre',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return UnidadMedida::class;
    }
}
