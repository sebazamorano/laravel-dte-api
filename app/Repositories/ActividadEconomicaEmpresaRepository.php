<?php

namespace App\Repositories;

use App\Models\ActividadEconomicaEmpresa;
use App\Repositories\Repository as BaseRepository;

/**
 * Class ActividadEconomicaEmpresaRepository.
 * @version October 3, 2018, 2:33 am UTC
 *
 * @method ActividadEconomicaEmpresa findWithoutFail($id, $columns = ['*'])
 * @method ActividadEconomicaEmpresa find($id, $columns = ['*'])
 * @method ActividadEconomicaEmpresa first($columns = ['*'])
 */
class ActividadEconomicaEmpresaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'actividad_economica_id',
        'empresa_id',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return ActividadEconomicaEmpresa::class;
    }
}
