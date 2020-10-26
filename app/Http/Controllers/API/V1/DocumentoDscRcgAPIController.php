<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\DocumentoDscRcg;
use App\Http\Controllers\AppBaseController;
use App\Repositories\DocumentoDscRcgRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Request\API\CreateDocumentoDscRcgAPIRequest;
use App\Http\Request\API\UpdateDocumentoDscRcgAPIRequest;

/**
 * Class DocumentoDscRcgController.
 */
class DocumentoDscRcgAPIController extends AppBaseController
{
    /** @var DocumentoDscRcgRepository */
    private $documentoDscRcgRepository;

    public function __construct(DocumentoDscRcgRepository $documentoDscRcgRepo)
    {
        $this->documentoDscRcgRepository = $documentoDscRcgRepo;
    }

    /**
     * Display a listing of the DocumentoDscRcg.
     * GET|HEAD /documentoDscRcgs.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->documentoDscRcgRepository->pushCriteria(new RequestCriteria($request));
        $this->documentoDscRcgRepository->pushCriteria(new LimitOffsetCriteria($request));
        $documentoDscRcgs = $this->documentoDscRcgRepository->all();

        return $this->sendResponse($documentoDscRcgs->toArray(), 'Documento Dsc Rcgs retrieved successfully');
    }

    /**
     * Store a newly created DocumentoDscRcg in storage.
     * POST /documentoDscRcgs.
     *
     * @param CreateDocumentoDscRcgAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoDscRcgAPIRequest $request)
    {
        $input = $request->all();

        $documentoDscRcgs = $this->documentoDscRcgRepository->create($input);

        return $this->sendResponse($documentoDscRcgs->toArray(), 'Documento Dsc Rcg saved successfully');
    }

    /**
     * Display the specified DocumentoDscRcg.
     * GET|HEAD /documentoDscRcgs/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DocumentoDscRcg $documentoDscRcg */
        $documentoDscRcg = $this->documentoDscRcgRepository->findWithoutFail($id);

        if (empty($documentoDscRcg)) {
            return $this->sendError('Documento Dsc Rcg not found');
        }

        return $this->sendResponse($documentoDscRcg->toArray(), 'Documento Dsc Rcg retrieved successfully');
    }

    /**
     * Update the specified DocumentoDscRcg in storage.
     * PUT/PATCH /documentoDscRcgs/{id}.
     *
     * @param  int $id
     * @param UpdateDocumentoDscRcgAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoDscRcgAPIRequest $request)
    {
        $input = $request->all();

        /** @var DocumentoDscRcg $documentoDscRcg */
        $documentoDscRcg = $this->documentoDscRcgRepository->findWithoutFail($id);

        if (empty($documentoDscRcg)) {
            return $this->sendError('Documento Dsc Rcg not found');
        }

        $documentoDscRcg = $this->documentoDscRcgRepository->update($input, $id);

        return $this->sendResponse($documentoDscRcg->toArray(), 'DocumentoDscRcg updated successfully');
    }

    /**
     * Remove the specified DocumentoDscRcg from storage.
     * DELETE /documentoDscRcgs/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DocumentoDscRcg $documentoDscRcg */
        $documentoDscRcg = $this->documentoDscRcgRepository->findWithoutFail($id);

        if (empty($documentoDscRcg)) {
            return $this->sendError('Documento Dsc Rcg not found');
        }

        $documentoDscRcg->delete();

        return $this->sendResponse($id, 'Documento Dsc Rcg deleted successfully');
    }
}
