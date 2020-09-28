<?php

namespace App\Repositories;

use App\Models\Contribuyente;
use App\Repositories\Repository as BaseRepository;

/**
 * Class ContribuyenteRepository.
 * @version January 3, 2019, 2:58 am -03
 *
 * @method Contribuyente findWithoutFail($id, $columns = ['*'])
 * @method Contribuyente find($id, $columns = ['*'])
 * @method Contribuyente first($columns = ['*'])
 */
class ContribuyenteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'rut',
        'razonSocial',
        'numeroResolucion',
        'fechaResolucion',
        'mail',
        'url',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Contribuyente::class;
    }
}
