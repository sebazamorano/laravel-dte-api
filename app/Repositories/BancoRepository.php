<?php

namespace App\Repositories;

use App\Models\Banco;
use App\Repositories\Repository as BaseRepository;

/**
 * Class BancoRepository.
 * @version January 13, 2019, 4:53 pm -03
 *
 * @method Banco findWithoutFail($id, $columns = ['*'])
 * @method Banco find($id, $columns = ['*'])
 * @method Banco first($columns = ['*'])
 */
class BancoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo',
        'nombre',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Banco::class;
    }
}
