<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Empresa;
use Response;
use Illuminate\Http\Request;
use App\Models\ActividadEconomicaEmpresa;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Repositories\ActividadEconomicaEmpresaRepository;
use App\Http\Requests\API\CreateActividadEconomicaEmpresaAPIRequest;
use App\Http\Requests\API\UpdateActividadEconomicaEmpresaAPIRequest;

/**
 * Class ActividadEconomicaEmpresaController.
 */
class ActividadEconomicaEmpresaAPIController extends AppBaseController
{
    /** @var ActividadEconomicaEmpresaRepository */
    private $actividadEconomicaEmpresaRepository;

    public function __construct(ActividadEconomicaEmpresaRepository $actividadEconomicaEmpresaRepo)
    {
        $this->actividadEconomicaEmpresaRepository = $actividadEconomicaEmpresaRepo;
    }

    /**
     * Store a newly created ActividadEconomicaEmpresa in storage.
     * POST /actividadesEconomicasEmpresas.
     *
     * @param CreateActividadEconomicaEmpresaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateActividadEconomicaEmpresaAPIRequest $request, $empresa_id)
    {
        $input = $request->validated();
        $empresa = Empresa::find($empresa_id);

        if($empresa === null){
            return $this->sendError('Empresa no existe');
        }
        $result = $empresa->actividadesEconomicasEmpresas()->sync($input);

        return $this->sendResponse($result, 'Actividades Economicas Empresa saved successfully');
    }
}
