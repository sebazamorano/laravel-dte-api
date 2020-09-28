<?php

namespace App\Repositories;

use App\Models\EmpresaParametro;
use App\Repositories\Repository as BaseRepository;

/**
 * Class EmpresaParametroRepository.
 * @version January 6, 2019, 2:29 am -03
 *
 * @method EmpresaParametro findWithoutFail($id, $columns = ['*'])
 * @method EmpresaParametro find($id, $columns = ['*'])
 * @method EmpresaParametro first($columns = ['*'])
 */
class EmpresaParametroRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'empresa_id',
        'nombre',
        'valor',
        'desde',
        'hasta',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return EmpresaParametro::class;
    }
}
