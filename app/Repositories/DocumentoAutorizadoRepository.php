<?php

namespace App\Repositories;

use App\Models\DocumentoAutorizado;
use App\Repositories\Repository as BaseRepository;

/**
 * Class DocumentoAutorizadoRepository.
 * @version October 3, 2018, 3:18 am UTC
 *
 * @method DocumentoAutorizado findWithoutFail($id, $columns = ['*'])
 * @method DocumentoAutorizado find($id, $columns = ['*'])
 * @method DocumentoAutorizado first($columns = ['*'])
 */
class DocumentoAutorizadoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'empresa_id',
        'tipo_documento_id',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return DocumentoAutorizado::class;
    }
}
