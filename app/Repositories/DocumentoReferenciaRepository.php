<?php

namespace App\Repositories;

use App\Models\DocumentoReferencia;
use App\Repositories\Repository as BaseRepository;

/**
 * Class DocumentoReferenciaRepository.
 * @version January 14, 2019, 11:01 am -03
 *
 * @method DocumentoReferencia findWithoutFail($id, $columns = ['*'])
 * @method DocumentoReferencia find($id, $columns = ['*'])
 * @method DocumentoReferencia first($columns = ['*'])
 */
class DocumentoReferenciaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'documento_id',
        'NroLinRef',
        'TpoDocRef',
        'IndGlobal',
        'FolioRef',
        'FchRef',
        'CodRef',
        'RazonRef',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return DocumentoReferencia::class;
    }
}
