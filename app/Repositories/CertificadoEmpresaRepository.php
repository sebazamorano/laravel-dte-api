<?php

namespace App\Repositories;

use App\Models\CertificadoEmpresa;
use App\Repositories\Repository as BaseRepository;

/**
 * Class CertificadoEmpresaRepository.
 * @version October 3, 2018, 2:28 am UTC
 *
 * @method CertificadoEmpresa findWithoutFail($id, $columns = ['*'])
 * @method CertificadoEmpresa find($id, $columns = ['*'])
 * @method CertificadoEmpresa first($columns = ['*'])
 */
class CertificadoEmpresaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'empresa_id',
        'original',
        'pem',
        'rut',
        'password',
        'fechaEmision',
        'fechaVencimiento',
        'subject',
        'issuer',
        'enUso',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return CertificadoEmpresa::class;
    }
}
