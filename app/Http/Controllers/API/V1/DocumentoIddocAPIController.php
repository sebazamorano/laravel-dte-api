<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\DocumentoIddoc;
use App\Http\Controllers\AppBaseController;
use App\Repositories\DocumentoIddocRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Request\API\CreateDocumentoIddocAPIRequest;
use App\Http\Request\API\UpdateDocumentoIddocAPIRequest;

/**
 * Class DocumentoIddocController.
 */
class DocumentoIddocAPIController extends AppBaseController
{
    /** @var DocumentoIddocRepository */
    private $documentoIddocRepository;

    public function __construct(DocumentoIddocRepository $documentoIddocRepo)
    {
        $this->documentoIddocRepository = $documentoIddocRepo;
    }

    /**
     * Display a listing of the DocumentoIddoc.
     * GET|HEAD /documentoIddocs.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->documentoIddocRepository->pushCriteria(new RequestCriteria($request));
        $this->documentoIddocRepository->pushCriteria(new LimitOffsetCriteria($request));
        $documentoIddocs = $this->documentoIddocRepository->all();

        return $this->sendResponse($documentoIddocs->toArray(), 'Documento Iddocs retrieved successfully');
    }

    /**
     * Store a newly created DocumentoIddoc in storage.
     * POST /documentoIddocs.
     *
     * @param CreateDocumentoIddocAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoIddocAPIRequest $request)
    {
        $input = $request->all();

        $documentoIddocs = $this->documentoIddocRepository->create($input);

        return $this->sendResponse($documentoIddocs->toArray(), 'Documento Iddoc saved successfully');
    }

    /**
     * Display the specified DocumentoIddoc.
     * GET|HEAD /documentoIddocs/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DocumentoIddoc $documentoIddoc */
        $documentoIddoc = $this->documentoIddocRepository->findWithoutFail($id);

        if (empty($documentoIddoc)) {
            return $this->sendError('Documento Iddoc not found');
        }

        return $this->sendResponse($documentoIddoc->toArray(), 'Documento Iddoc retrieved successfully');
    }

    /**
     * Update the specified DocumentoIddoc in storage.
     * PUT/PATCH /documentoIddocs/{id}.
     *
     * @param  int $id
     * @param UpdateDocumentoIddocAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoIddocAPIRequest $request)
    {
        $input = $request->all();

        /** @var DocumentoIddoc $documentoIddoc */
        $documentoIddoc = $this->documentoIddocRepository->findWithoutFail($id);

        if (empty($documentoIddoc)) {
            return $this->sendError('Documento Iddoc not found');
        }

        $documentoIddoc = $this->documentoIddocRepository->update($input, $id);

        return $this->sendResponse($documentoIddoc->toArray(), 'DocumentoIddoc updated successfully');
    }

    /**
     * Remove the specified DocumentoIddoc from storage.
     * DELETE /documentoIddocs/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DocumentoIddoc $documentoIddoc */
        $documentoIddoc = $this->documentoIddocRepository->findWithoutFail($id);

        if (empty($documentoIddoc)) {
            return $this->sendError('Documento Iddoc not found');
        }

        $documentoIddoc->delete();

        return $this->sendResponse($id, 'Documento Iddoc deleted successfully');
    }
}
