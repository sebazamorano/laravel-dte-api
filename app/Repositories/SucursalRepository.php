<?php

namespace App\Repositories;

use App\Models\Sucursal;
use App\Repositories\Repository as BaseRepository;

/**
 * Class SucursalRepository.
 * @version October 2, 2018, 4:12 am UTC
 *
 * @method Sucursal findWithoutFail($id, $columns = ['*'])
 * @method Sucursal find($id, $columns = ['*'])
 * @method Sucursal first($columns = ['*'])
 */
class SucursalRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'empresa_id',
        'tipo',
        'codigo',
        'nombre',
        'direccion',
        'direccionXml',
        'comuna',
        'ciudad',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Sucursal::class;
    }
}
