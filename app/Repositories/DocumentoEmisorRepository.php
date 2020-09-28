<?php

namespace App\Repositories;

use App\Models\DocumentoEmisor;
use App\Repositories\Repository as BaseRepository;

/**
 * Class DocumentoEmisorRepository.
 * @version January 14, 2019, 1:41 am -03
 *
 * @method DocumentoEmisor findWithoutFail($id, $columns = ['*'])
 * @method DocumentoEmisor find($id, $columns = ['*'])
 * @method DocumentoEmisor first($columns = ['*'])
 */
class DocumentoEmisorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'documento_id',
        'RUTEmisor',
        'RznSoc',
        'GiroEmis',
        'Telefono1',
        'Telefono2',
        'CorreoEmisor',
        'Sucursal',
        'CdgSIISucur',
        'CodAdicSucur',
        'DirOrigen',
        'CmnaOrigen',
        'CiudadOrigen',
        'CdgVendedor',
        'IdAdicEmisor',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return DocumentoEmisor::class;
    }
}
