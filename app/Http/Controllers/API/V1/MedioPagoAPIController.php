<?php

namespace App\Http\Controllers\API\V1;

use Response;
use App\Models\MedioPago;
use Illuminate\Http\Request;
use App\Repositories\MedioPagoRepository;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Request\API\CreateMedioPagoAPIRequest;
use App\Http\Request\API\UpdateMedioPagoAPIRequest;

/**
 * Class MedioPagoController.
 */
class MedioPagoAPIController extends AppBaseController
{
    /** @var MedioPagoRepository */
    private $medioPagoRepository;

    public function __construct(MedioPagoRepository $medioPagoRepo)
    {
        $this->medioPagoRepository = $medioPagoRepo;
    }

    /**
     * Display a listing of the MedioPago.
     * GET|HEAD /mediosPagos.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->medioPagoRepository->pushCriteria(new RequestCriteria($request));
        $this->medioPagoRepository->pushCriteria(new LimitOffsetCriteria($request));
        $mediosPagos = $this->medioPagoRepository->all();

        return $this->sendResponse($mediosPagos->toArray(), 'Medios Pagos retrieved successfully');
    }

    /**
     * Store a newly created MedioPago in storage.
     * POST /mediosPagos.
     *
     * @param CreateMedioPagoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMedioPagoAPIRequest $request)
    {
        $input = $request->all();

        $mediosPagos = $this->medioPagoRepository->create($input);

        return $this->sendResponse($mediosPagos->toArray(), 'Medio Pago saved successfully');
    }

    /**
     * Display the specified MedioPago.
     * GET|HEAD /mediosPagos/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MedioPago $medioPago */
        $medioPago = $this->medioPagoRepository->findWithoutFail($id);

        if (empty($medioPago)) {
            return $this->sendError('Medio Pago not found');
        }

        return $this->sendResponse($medioPago->toArray(), 'Medio Pago retrieved successfully');
    }

    /**
     * Update the specified MedioPago in storage.
     * PUT/PATCH /mediosPagos/{id}.
     *
     * @param  int $id
     * @param UpdateMedioPagoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMedioPagoAPIRequest $request)
    {
        $input = $request->all();

        /** @var MedioPago $medioPago */
        $medioPago = $this->medioPagoRepository->findWithoutFail($id);

        if (empty($medioPago)) {
            return $this->sendError('Medio Pago not found');
        }

        $medioPago = $this->medioPagoRepository->update($input, $id);

        return $this->sendResponse($medioPago->toArray(), 'MedioPago updated successfully');
    }

    /**
     * Remove the specified MedioPago from storage.
     * DELETE /mediosPagos/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MedioPago $medioPago */
        $medioPago = $this->medioPagoRepository->findWithoutFail($id);

        if (empty($medioPago)) {
            return $this->sendError('Medio Pago not found');
        }

        $medioPago->delete();

        return $this->sendResponse($id, 'Medio Pago deleted successfully');
    }
}
