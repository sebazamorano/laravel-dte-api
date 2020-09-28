<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\EnvioDteRevision;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EnvioDteRevisionRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Requests\API\CreateEnvioDteRevisionAPIRequest;
use App\Http\Requests\API\UpdateEnvioDteRevisionAPIRequest;

/**
 * Class EnvioDteRevisionController.
 */
class EnvioDteRevisionAPIController extends AppBaseController
{
    /** @var EnvioDteRevisionRepository */
    private $envioDteRevisionRepository;

    public function __construct(EnvioDteRevisionRepository $envioDteRevisionRepo)
    {
        $this->envioDteRevisionRepository = $envioDteRevisionRepo;
    }

    /**
     * Display a listing of the EnvioDteRevision.
     * GET|HEAD /envioDteRevisions.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->envioDteRevisionRepository->pushCriteria(new RequestCriteria($request));
        $this->envioDteRevisionRepository->pushCriteria(new LimitOffsetCriteria($request));
        $envioDteRevisions = $this->envioDteRevisionRepository->all();

        return $this->sendResponse($envioDteRevisions->toArray(), 'Envio Dte Revisions retrieved successfully');
    }

    /**
     * Store a newly created EnvioDteRevision in storage.
     * POST /envioDteRevisions.
     *
     * @param CreateEnvioDteRevisionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEnvioDteRevisionAPIRequest $request)
    {
        $input = $request->all();

        $envioDteRevisions = $this->envioDteRevisionRepository->create($input);

        return $this->sendResponse($envioDteRevisions->toArray(), 'Envio Dte Revision saved successfully');
    }

    /**
     * Display the specified EnvioDteRevision.
     * GET|HEAD /envioDteRevisions/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var EnvioDteRevision $envioDteRevision */
        $envioDteRevision = $this->envioDteRevisionRepository->findWithoutFail($id);

        if (empty($envioDteRevision)) {
            return $this->sendError('Envio Dte Revision not found');
        }

        return $this->sendResponse($envioDteRevision->toArray(), 'Envio Dte Revision retrieved successfully');
    }

    /**
     * Update the specified EnvioDteRevision in storage.
     * PUT/PATCH /envioDteRevisions/{id}.
     *
     * @param  int $id
     * @param UpdateEnvioDteRevisionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEnvioDteRevisionAPIRequest $request)
    {
        $input = $request->all();

        /** @var EnvioDteRevision $envioDteRevision */
        $envioDteRevision = $this->envioDteRevisionRepository->findWithoutFail($id);

        if (empty($envioDteRevision)) {
            return $this->sendError('Envio Dte Revision not found');
        }

        $envioDteRevision = $this->envioDteRevisionRepository->update($input, $id);

        return $this->sendResponse($envioDteRevision->toArray(), 'EnvioDteRevision updated successfully');
    }

    /**
     * Remove the specified EnvioDteRevision from storage.
     * DELETE /envioDteRevisions/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var EnvioDteRevision $envioDteRevision */
        $envioDteRevision = $this->envioDteRevisionRepository->findWithoutFail($id);

        if (empty($envioDteRevision)) {
            return $this->sendError('Envio Dte Revision not found');
        }

        $envioDteRevision->delete();

        return $this->sendResponse($id, 'Envio Dte Revision deleted successfully');
    }
}
