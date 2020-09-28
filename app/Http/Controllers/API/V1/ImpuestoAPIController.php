<?php

namespace App\Http\Controllers\API\V1;

use Response;
use App\Models\Impuesto;
use Illuminate\Http\Request;
use App\Repositories\ImpuestoRepository;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Requests\API\CreateImpuestoAPIRequest;
use App\Http\Requests\API\UpdateImpuestoAPIRequest;

/**
 * Class ImpuestoController.
 */
class ImpuestoAPIController extends AppBaseController
{
    /** @var ImpuestoRepository */
    private $impuestoRepository;

    public function __construct(ImpuestoRepository $impuestoRepo)
    {
        $this->impuestoRepository = $impuestoRepo;
    }

    /**
     * Display a listing of the Impuesto.
     * GET|HEAD /impuestos.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->impuestoRepository->pushCriteria(new RequestCriteria($request));
        $this->impuestoRepository->pushCriteria(new LimitOffsetCriteria($request));
        $impuestos = $this->impuestoRepository->all();

        return $this->sendResponse($impuestos->toArray(), 'Impuestos retrieved successfully');
    }

    /**
     * Store a newly created Impuesto in storage.
     * POST /impuestos.
     *
     * @param CreateImpuestoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateImpuestoAPIRequest $request)
    {
        $input = $request->all();

        $impuestos = $this->impuestoRepository->create($input);

        return $this->sendResponse($impuestos->toArray(), 'Impuesto saved successfully');
    }

    /**
     * Display the specified Impuesto.
     * GET|HEAD /impuestos/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Impuesto $impuesto */
        $impuesto = $this->impuestoRepository->findWithoutFail($id);

        if (empty($impuesto)) {
            return $this->sendError('Impuesto not found');
        }

        return $this->sendResponse($impuesto->toArray(), 'Impuesto retrieved successfully');
    }

    /**
     * Update the specified Impuesto in storage.
     * PUT/PATCH /impuestos/{id}.
     *
     * @param  int $id
     * @param UpdateImpuestoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImpuestoAPIRequest $request)
    {
        $input = $request->all();

        /** @var Impuesto $impuesto */
        $impuesto = $this->impuestoRepository->findWithoutFail($id);

        if (empty($impuesto)) {
            return $this->sendError('Impuesto not found');
        }

        $impuesto = $this->impuestoRepository->update($input, $id);

        return $this->sendResponse($impuesto->toArray(), 'Impuesto updated successfully');
    }

    /**
     * Remove the specified Impuesto from storage.
     * DELETE /impuestos/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Impuesto $impuesto */
        $impuesto = $this->impuestoRepository->findWithoutFail($id);

        if (empty($impuesto)) {
            return $this->sendError('Impuesto not found');
        }

        $impuesto->delete();

        return $this->sendResponse($id, 'Impuesto deleted successfully');
    }
}
