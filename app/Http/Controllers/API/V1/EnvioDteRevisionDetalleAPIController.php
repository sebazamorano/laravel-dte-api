<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\EnvioDteRevisionDetalle;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Repositories\EnvioDteRevisionDetalleRepository;
use App\Http\Request\API\CreateEnvioDteRevisionDetalleAPIRequest;
use App\Http\Request\API\UpdateEnvioDteRevisionDetalleAPIRequest;

/**
 * Class EnvioDteRevisionDetalleController.
 */
class EnvioDteRevisionDetalleAPIController extends AppBaseController
{
    /** @var EnvioDteRevisionDetalleRepository */
    private $envioDteRevisionDetalleRepository;

    public function __construct(EnvioDteRevisionDetalleRepository $envioDteRevisionDetalleRepo)
    {
        $this->envioDteRevisionDetalleRepository = $envioDteRevisionDetalleRepo;
    }

    /**
     * Display a listing of the EnvioDteRevisionDetalle.
     * GET|HEAD /envioDteRevisionDetalles.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->envioDteRevisionDetalleRepository->pushCriteria(new RequestCriteria($request));
        $this->envioDteRevisionDetalleRepository->pushCriteria(new LimitOffsetCriteria($request));
        $envioDteRevisionDetalles = $this->envioDteRevisionDetalleRepository->all();

        return $this->sendResponse($envioDteRevisionDetalles->toArray(), 'Envio Dte Revision Detalles retrieved successfully');
    }

    /**
     * Store a newly created EnvioDteRevisionDetalle in storage.
     * POST /envioDteRevisionDetalles.
     *
     * @param CreateEnvioDteRevisionDetalleAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEnvioDteRevisionDetalleAPIRequest $request)
    {
        $input = $request->all();

        $envioDteRevisionDetalles = $this->envioDteRevisionDetalleRepository->create($input);

        return $this->sendResponse($envioDteRevisionDetalles->toArray(), 'Envio Dte Revision Detalle saved successfully');
    }

    /**
     * Display the specified EnvioDteRevisionDetalle.
     * GET|HEAD /envioDteRevisionDetalles/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var EnvioDteRevisionDetalle $envioDteRevisionDetalle */
        $envioDteRevisionDetalle = $this->envioDteRevisionDetalleRepository->findWithoutFail($id);

        if (empty($envioDteRevisionDetalle)) {
            return $this->sendError('Envio Dte Revision Detalle not found');
        }

        return $this->sendResponse($envioDteRevisionDetalle->toArray(), 'Envio Dte Revision Detalle retrieved successfully');
    }

    /**
     * Update the specified EnvioDteRevisionDetalle in storage.
     * PUT/PATCH /envioDteRevisionDetalles/{id}.
     *
     * @param  int $id
     * @param UpdateEnvioDteRevisionDetalleAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEnvioDteRevisionDetalleAPIRequest $request)
    {
        $input = $request->all();

        /** @var EnvioDteRevisionDetalle $envioDteRevisionDetalle */
        $envioDteRevisionDetalle = $this->envioDteRevisionDetalleRepository->findWithoutFail($id);

        if (empty($envioDteRevisionDetalle)) {
            return $this->sendError('Envio Dte Revision Detalle not found');
        }

        $envioDteRevisionDetalle = $this->envioDteRevisionDetalleRepository->update($input, $id);

        return $this->sendResponse($envioDteRevisionDetalle->toArray(), 'EnvioDteRevisionDetalle updated successfully');
    }

    /**
     * Remove the specified EnvioDteRevisionDetalle from storage.
     * DELETE /envioDteRevisionDetalles/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var EnvioDteRevisionDetalle $envioDteRevisionDetalle */
        $envioDteRevisionDetalle = $this->envioDteRevisionDetalleRepository->findWithoutFail($id);

        if (empty($envioDteRevisionDetalle)) {
            return $this->sendError('Envio Dte Revision Detalle not found');
        }

        $envioDteRevisionDetalle->delete();

        return $this->sendResponse($id, 'Envio Dte Revision Detalle deleted successfully');
    }
}
