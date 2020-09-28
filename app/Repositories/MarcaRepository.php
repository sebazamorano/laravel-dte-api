<?php

namespace App\Repositories;

use App\Models\Marca;
use App\Repositories\Repository as BaseRepository;

/**
 * Class MarcaRepository.
 * @version January 13, 2019, 6:20 pm -03
 *
 * @method Marca findWithoutFail($id, $columns = ['*'])
 * @method Marca find($id, $columns = ['*'])
 * @method Marca first($columns = ['*'])
 */
class MarcaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'empresa_id',
        'nombre',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Marca::class;
    }
}
