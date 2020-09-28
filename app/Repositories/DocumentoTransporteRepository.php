<?php

namespace App\Repositories;

use App\Models\DocumentoTransporte;
use App\Repositories\Repository as BaseRepository;

/**
 * Class DocumentoTransporteRepository.
 * @version January 14, 2019, 12:00 pm -03
 *
 * @method DocumentoTransporte findWithoutFail($id, $columns = ['*'])
 * @method DocumentoTransporte find($id, $columns = ['*'])
 * @method DocumentoTransporte first($columns = ['*'])
 */
class DocumentoTransporteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'documento_id',
        'Patente',
        'RUTTrans',
        'RUTChofer',
        'NombreChofer',
        'DirDest',
        'CmnaDest',
        'CiudadDest',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return DocumentoTransporte::class;
    }
}
