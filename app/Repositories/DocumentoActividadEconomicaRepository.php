<?php

namespace App\Repositories;

use App\Models\DocumentoActividadEconomica;
use App\Repositories\Repository as BaseRepository;

/**
 * Class DocumentoActividadEconomicaRepository.
 * @version January 14, 2019, 12:11 pm -03
 *
 * @method DocumentoActividadEconomica findWithoutFail($id, $columns = ['*'])
 * @method DocumentoActividadEconomica find($id, $columns = ['*'])
 * @method DocumentoActividadEconomica first($columns = ['*'])
 */
class DocumentoActividadEconomicaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'documento_id',
        'acteco',
        'descripcion',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return DocumentoActividadEconomica::class;
    }
}
