<?php

namespace App\Repositories;

use App\Models\DetalleRespuestaDteXml;
use App\Repositories\Repository as BaseRepository;

/**
 * Class DetalleRespuestaDteXmlRepository.
 * @version February 24, 2019, 1:30 am -03
 *
 * @method DetalleRespuestaDteXml findWithoutFail($id, $columns = ['*'])
 * @method DetalleRespuestaDteXml find($id, $columns = ['*'])
 * @method DetalleRespuestaDteXml first($columns = ['*'])
 */
class DetalleRespuestaDteXmlRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'respuesta_dte_xml_id',
        'tipoDte',
        'folio',
        'fchEmis',
        'rutEmisor',
        'rutRecep',
        'mntTotal',
        'estado',
        'glosa',
        'digestValue',
        'respuestaDteXml.empresa_id',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return DetalleRespuestaDteXml::class;
    }
}
