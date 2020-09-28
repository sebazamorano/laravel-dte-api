<?php

namespace App\Repositories;

use App\Models\DocumentoReceptor;
use App\Repositories\Repository as BaseRepository;

/**
 * Class DocumentoReceptorRepository.
 * @version January 14, 2019, 1:50 am -03
 *
 * @method DocumentoReceptor findWithoutFail($id, $columns = ['*'])
 * @method DocumentoReceptor find($id, $columns = ['*'])
 * @method DocumentoReceptor first($columns = ['*'])
 */
class DocumentoReceptorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'documento_id',
        'RUTRecep',
        'CdgIntRecep',
        'RznSocRecep',
        'NumId',
        'Nacionalidad',
        'TipoDocID',
        'IdAdicRecep',
        'GiroRecep',
        'Contacto',
        'CorreoRecep',
        'DirRecep',
        'CmnaRecep',
        'CiudadRecep',
        'DirPostal',
        'CmnaPostal',
        'CiudadPostal',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return DocumentoReceptor::class;
    }
}
