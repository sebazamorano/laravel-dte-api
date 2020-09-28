<?php

namespace App\Http\Controllers\API\V1;

use Response;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Repositories\UnidadMedidaRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Requests\API\CreateUnidadMedidaAPIRequest;
use App\Http\Requests\API\UpdateUnidadMedidaAPIRequest;

/**
 * Class UnidadMedidaController.
 */
class UnidadMedidaAPIController extends AppBaseController
{
    /** @var UnidadMedidaRepository */
    private $unidadMedidaRepository;

    public function __construct(UnidadMedidaRepository $unidadMedidaRepo)
    {
        $this->unidadMedidaRepository = $unidadMedidaRepo;
    }

    /**
     * Display a listing of the UnidadMedida.
     * GET|HEAD /unidadesMedidas.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->unidadMedidaRepository->pushCriteria(new RequestCriteria($request));
        $this->unidadMedidaRepository->pushCriteria(new LimitOffsetCriteria($request));
        $unidadesMedidas = $this->unidadMedidaRepository->all();

        return $this->sendResponse($unidadesMedidas->toArray(), 'Unidades Medidas retrieved successfully');
    }

    /**
     * Store a newly created UnidadMedida in storage.
     * POST /unidadesMedidas.
     *
     * @param CreateUnidadMedidaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUnidadMedidaAPIRequest $request)
    {
        $input = $request->all();

        $unidadesMedidas = $this->unidadMedidaRepository->create($input);

        return $this->sendResponse($unidadesMedidas->toArray(), 'Unidad Medida saved successfully');
    }

    /**
     * Display the specified UnidadMedida.
     * GET|HEAD /unidadesMedidas/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var UnidadMedida $unidadMedida */
        $unidadMedida = $this->unidadMedidaRepository->findWithoutFail($id);

        if (empty($unidadMedida)) {
            return $this->sendError('Unidad Medida not found');
        }

        return $this->sendResponse($unidadMedida->toArray(), 'Unidad Medida retrieved successfully');
    }

    /**
     * Update the specified UnidadMedida in storage.
     * PUT/PATCH /unidadesMedidas/{id}.
     *
     * @param  int $id
     * @param UpdateUnidadMedidaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUnidadMedidaAPIRequest $request)
    {
        $input = $request->all();

        /** @var UnidadMedida $unidadMedida */
        $unidadMedida = $this->unidadMedidaRepository->findWithoutFail($id);

        if (empty($unidadMedida)) {
            return $this->sendError('Unidad Medida not found');
        }

        $unidadMedida = $this->unidadMedidaRepository->update($input, $id);

        return $this->sendResponse($unidadMedida->toArray(), 'UnidadMedida updated successfully');
    }

    /**
     * Remove the specified UnidadMedida from storage.
     * DELETE /unidadesMedidas/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var UnidadMedida $unidadMedida */
        $unidadMedida = $this->unidadMedidaRepository->findWithoutFail($id);

        if (empty($unidadMedida)) {
            return $this->sendError('Unidad Medida not found');
        }

        $unidadMedida->delete();

        return $this->sendResponse($id, 'Unidad Medida deleted successfully');
    }
}
