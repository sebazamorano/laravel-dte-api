<?php

namespace App\Repositories;

use App\Models\Empresa;
use App\Repositories\Repository as BaseRepository;

/**
 * Class EmpresaRepository.
 * @version October 1, 2018, 4:58 am UTC
 *
 * @method Empresa findWithoutFail($id, $columns = ['*'])
 * @method Empresa find($id, $columns = ['*'])
 * @method Empresa first($columns = ['*'])
 */
class EmpresaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'rut',
        'razonSocial',
        'direccion',
        'region_id',
        'provincia_id',
        'comuna_id',
        'giro',
        'correoRecepcion',
        'passwordCorreo',
        'servidorSmtp',
        'fechaResolucion',
        'numeroResolucion',
        'fechaResolucionBoleta',
        'numeroResolucionBoleta',
        'nombreLogotipo',
        'archivoLogotipo',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Empresa::class;
    }
}
