<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\EstadisticaEnvio;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EstadisticaEnvioRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Request\API\CreateEstadisticaEnvioAPIRequest;
use App\Http\Request\API\UpdateEstadisticaEnvioAPIRequest;

/**
 * Class EstadisticaEnvioController.
 */
class EstadisticaEnvioAPIController extends AppBaseController
{
    /** @var EstadisticaEnvioRepository */
    private $estadisticaEnvioRepository;

    public function __construct(EstadisticaEnvioRepository $estadisticaEnvioRepo)
    {
        $this->estadisticaEnvioRepository = $estadisticaEnvioRepo;
    }

    /**
     * Display a listing of the EstadisticaEnvio.
     * GET|HEAD /estadisticaEnvios.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->estadisticaEnvioRepository->pushCriteria(new RequestCriteria($request));
        $this->estadisticaEnvioRepository->pushCriteria(new LimitOffsetCriteria($request));
        $estadisticaEnvios = $this->estadisticaEnvioRepository->all();

        return $this->sendResponse($estadisticaEnvios->toArray(), 'Estadistica Envios retrieved successfully');
    }

    /**
     * Store a newly created EstadisticaEnvio in storage.
     * POST /estadisticaEnvios.
     *
     * @param CreateEstadisticaEnvioAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEstadisticaEnvioAPIRequest $request)
    {
        $input = $request->all();

        $estadisticaEnvios = $this->estadisticaEnvioRepository->create($input);

        return $this->sendResponse($estadisticaEnvios->toArray(), 'Estadistica Envio saved successfully');
    }

    /**
     * Display the specified EstadisticaEnvio.
     * GET|HEAD /estadisticaEnvios/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var EstadisticaEnvio $estadisticaEnvio */
        $estadisticaEnvio = $this->estadisticaEnvioRepository->findWithoutFail($id);

        if (empty($estadisticaEnvio)) {
            return $this->sendError('Estadistica Envio not found');
        }

        return $this->sendResponse($estadisticaEnvio->toArray(), 'Estadistica Envio retrieved successfully');
    }

    /**
     * Update the specified EstadisticaEnvio in storage.
     * PUT/PATCH /estadisticaEnvios/{id}.
     *
     * @param  int $id
     * @param UpdateEstadisticaEnvioAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEstadisticaEnvioAPIRequest $request)
    {
        $input = $request->all();

        /** @var EstadisticaEnvio $estadisticaEnvio */
        $estadisticaEnvio = $this->estadisticaEnvioRepository->findWithoutFail($id);

        if (empty($estadisticaEnvio)) {
            return $this->sendError('Estadistica Envio not found');
        }

        $estadisticaEnvio = $this->estadisticaEnvioRepository->update($input, $id);

        return $this->sendResponse($estadisticaEnvio->toArray(), 'EstadisticaEnvio updated successfully');
    }

    /**
     * Remove the specified EstadisticaEnvio from storage.
     * DELETE /estadisticaEnvios/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var EstadisticaEnvio $estadisticaEnvio */
        $estadisticaEnvio = $this->estadisticaEnvioRepository->findWithoutFail($id);

        if (empty($estadisticaEnvio)) {
            return $this->sendError('Estadistica Envio not found');
        }

        $estadisticaEnvio->delete();

        return $this->sendResponse($id, 'Estadistica Envio deleted successfully');
    }
}
