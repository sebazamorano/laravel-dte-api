<?php

namespace App\Repositories;

use App\Models\RespuestaDteXml;
use App\Repositories\Repository as BaseRepository;

/**
 * Class RespuestaDteXmlRepository.
 * @version February 24, 2019, 12:57 am -03
 *
 * @method RespuestaDteXml findWithoutFail($id, $columns = ['*'])
 * @method RespuestaDteXml find($id, $columns = ['*'])
 * @method RespuestaDteXml first($columns = ['*'])
 */
class RespuestaDteXmlRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'empresa_id',
        'envio_dte_id',
        'tipoRespuesta',
        'rutResponde',
        'rutRecibe',
        'nmbContacto',
        'fonoContacto',
        'mailContacto',
        'tmstFirmaResp',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return RespuestaDteXml::class;
    }
}
