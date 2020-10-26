<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\EnvioDteError;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EnvioDteErrorRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Request\API\CreateEnvioDteErrorAPIRequest;
use App\Http\Request\API\UpdateEnvioDteErrorAPIRequest;

/**
 * Class EnvioDteErrorController.
 */
class EnvioDteErrorAPIController extends AppBaseController
{
    /** @var EnvioDteErrorRepository */
    private $envioDteErrorRepository;

    public function __construct(EnvioDteErrorRepository $envioDteErrorRepo)
    {
        $this->envioDteErrorRepository = $envioDteErrorRepo;
    }

    /**
     * Display a listing of the EnvioDteError.
     * GET|HEAD /envioDteErrors.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->envioDteErrorRepository->pushCriteria(new RequestCriteria($request));
        $this->envioDteErrorRepository->pushCriteria(new LimitOffsetCriteria($request));
        $envioDteErrors = $this->envioDteErrorRepository->all();

        return $this->sendResponse($envioDteErrors->toArray(), 'Envio Dte Errors retrieved successfully');
    }

    /**
     * Store a newly created EnvioDteError in storage.
     * POST /envioDteErrors.
     *
     * @param CreateEnvioDteErrorAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEnvioDteErrorAPIRequest $request)
    {
        $input = $request->all();

        $envioDteErrors = $this->envioDteErrorRepository->create($input);

        return $this->sendResponse($envioDteErrors->toArray(), 'Envio Dte Error saved successfully');
    }

    /**
     * Display the specified EnvioDteError.
     * GET|HEAD /envioDteErrors/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var EnvioDteError $envioDteError */
        $envioDteError = $this->envioDteErrorRepository->findWithoutFail($id);

        if (empty($envioDteError)) {
            return $this->sendError('Envio Dte Error not found');
        }

        return $this->sendResponse($envioDteError->toArray(), 'Envio Dte Error retrieved successfully');
    }

    /**
     * Update the specified EnvioDteError in storage.
     * PUT/PATCH /envioDteErrors/{id}.
     *
     * @param  int $id
     * @param UpdateEnvioDteErrorAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEnvioDteErrorAPIRequest $request)
    {
        $input = $request->all();

        /** @var EnvioDteError $envioDteError */
        $envioDteError = $this->envioDteErrorRepository->findWithoutFail($id);

        if (empty($envioDteError)) {
            return $this->sendError('Envio Dte Error not found');
        }

        $envioDteError = $this->envioDteErrorRepository->update($input, $id);

        return $this->sendResponse($envioDteError->toArray(), 'EnvioDteError updated successfully');
    }

    /**
     * Remove the specified EnvioDteError from storage.
     * DELETE /envioDteErrors/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var EnvioDteError $envioDteError */
        $envioDteError = $this->envioDteErrorRepository->findWithoutFail($id);

        if (empty($envioDteError)) {
            return $this->sendError('Envio Dte Error not found');
        }

        $envioDteError->delete();

        return $this->sendResponse($id, 'Envio Dte Error deleted successfully');
    }
}
