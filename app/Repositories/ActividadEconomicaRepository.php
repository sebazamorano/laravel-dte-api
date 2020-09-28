<?php

namespace App\Repositories;

use App\Models\ActividadEconomica;
use App\Repositories\Repository as BaseRepository;

/**
 * Class ActividadEconomicaRepository.
 * @version October 2, 2018, 4:27 am UTC
 *
 * @method ActividadEconomica findWithoutFail($id, $columns = ['*'])
 * @method ActividadEconomica find($id, $columns = ['*'])
 * @method ActividadEconomica first($columns = ['*'])
 */
class ActividadEconomicaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo',
        'descripcion',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return ActividadEconomica::class;
    }
}
