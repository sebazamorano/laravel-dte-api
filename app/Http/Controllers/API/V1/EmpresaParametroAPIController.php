<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\EmpresaParametro;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EmpresaParametroRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Requests\API\CreateEmpresaParametroAPIRequest;
use App\Http\Requests\API\UpdateEmpresaParametroAPIRequest;

/**
 * Class EmpresaParametroController.
 */
class EmpresaParametroAPIController extends AppBaseController
{
    /** @var EmpresaParametroRepository */
    private $empresaParametroRepository;

    public function __construct(EmpresaParametroRepository $empresaParametroRepo)
    {
        $this->empresaParametroRepository = $empresaParametroRepo;
    }

    /**
     * Display a listing of the EmpresaParametro.
     * GET|HEAD /empresasParametros.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->empresaParametroRepository->pushCriteria(new RequestCriteria($request));
        $this->empresaParametroRepository->pushCriteria(new LimitOffsetCriteria($request));
        $empresasParametros = $this->empresaParametroRepository->all();

        return $this->sendResponse($empresasParametros->toArray(), 'Empresas Parametros retrieved successfully');
    }

    /**
     * Store a newly created EmpresaParametro in storage.
     * POST /empresasParametros.
     *
     * @param CreateEmpresaParametroAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEmpresaParametroAPIRequest $request)
    {
        $input = $request->all();

        $empresasParametros = $this->empresaParametroRepository->create($input);

        return $this->sendResponse($empresasParametros->toArray(), 'Empresa Parametro saved successfully');
    }

    /**
     * Display the specified EmpresaParametro.
     * GET|HEAD /empresasParametros/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($empresa_id, $id)
    {
        /** @var EmpresaParametro $empresaParametro */
        $empresaParametro = $this->empresaParametroRepository->findWithoutFail($id);

        if (empty($empresaParametro)) {
            return $this->sendError('Empresa Parametro not found');
        }

        return $this->sendResponse($empresaParametro->toArray(), 'Empresa Parametro retrieved successfully');
    }

    /**
     * Update the specified EmpresaParametro in storage.
     * PUT/PATCH /empresasParametros/{id}.
     *
     * @param  int $id
     * @param UpdateEmpresaParametroAPIRequest $request
     *
     * @return Response
     */
    public function update($empresa_id, $id, UpdateEmpresaParametroAPIRequest $request)
    {
        $input = $request->all();

        /** @var EmpresaParametro $empresaParametro */
        $empresaParametro = $this->empresaParametroRepository->findWithoutFail($id);

        if (empty($empresaParametro)) {
            return $this->sendError('Empresa Parametro not found');
        }

        $empresaParametro = $this->empresaParametroRepository->update($input, $id);

        return $this->sendResponse($empresaParametro->toArray(), 'EmpresaParametro updated successfully');
    }

    /**
     * Remove the specified EmpresaParametro from storage.
     * DELETE /empresasParametros/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($empresa_id, $id)
    {
        /** @var EmpresaParametro $empresaParametro */
        $empresaParametro = $this->empresaParametroRepository->findWithoutFail($id);

        if (empty($empresaParametro)) {
            return $this->sendError('Empresa Parametro not found');
        }

        $empresaParametro->delete();

        return $this->sendResponse($id, 'Empresa Parametro deleted successfully');
    }
}
