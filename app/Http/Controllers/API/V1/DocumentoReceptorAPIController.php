<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\DocumentoReceptor;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\DocumentoReceptorRepository;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Request\API\CreateDocumentoReceptorAPIRequest;
use App\Http\Request\API\UpdateDocumentoReceptorAPIRequest;

/**
 * Class DocumentoReceptorController.
 */
class DocumentoReceptorAPIController extends AppBaseController
{
    /** @var DocumentoReceptorRepository */
    private $documentoReceptorRepository;

    public function __construct(DocumentoReceptorRepository $documentoReceptorRepo)
    {
        $this->documentoReceptorRepository = $documentoReceptorRepo;
    }

    /**
     * Display a listing of the DocumentoReceptor.
     * GET|HEAD /documentosReceptor.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->documentoReceptorRepository->pushCriteria(new RequestCriteria($request));
        $this->documentoReceptorRepository->pushCriteria(new LimitOffsetCriteria($request));
        $documentosReceptor = $this->documentoReceptorRepository->all();

        return $this->sendResponse($documentosReceptor->toArray(), 'Documentos Receptor retrieved successfully');
    }

    /**
     * Store a newly created DocumentoReceptor in storage.
     * POST /documentosReceptor.
     *
     * @param CreateDocumentoReceptorAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoReceptorAPIRequest $request)
    {
        $input = $request->all();

        $documentosReceptor = $this->documentoReceptorRepository->create($input);

        return $this->sendResponse($documentosReceptor->toArray(), 'Documento Receptor saved successfully');
    }

    /**
     * Display the specified DocumentoReceptor.
     * GET|HEAD /documentosReceptor/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DocumentoReceptor $documentoReceptor */
        $documentoReceptor = $this->documentoReceptorRepository->findWithoutFail($id);

        if (empty($documentoReceptor)) {
            return $this->sendError('Documento Receptor not found');
        }

        return $this->sendResponse($documentoReceptor->toArray(), 'Documento Receptor retrieved successfully');
    }

    /**
     * Update the specified DocumentoReceptor in storage.
     * PUT/PATCH /documentosReceptor/{id}.
     *
     * @param  int $id
     * @param UpdateDocumentoReceptorAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoReceptorAPIRequest $request)
    {
        $input = $request->all();

        /** @var DocumentoReceptor $documentoReceptor */
        $documentoReceptor = $this->documentoReceptorRepository->findWithoutFail($id);

        if (empty($documentoReceptor)) {
            return $this->sendError('Documento Receptor not found');
        }

        $documentoReceptor = $this->documentoReceptorRepository->update($input, $id);

        return $this->sendResponse($documentoReceptor->toArray(), 'DocumentoReceptor updated successfully');
    }

    /**
     * Remove the specified DocumentoReceptor from storage.
     * DELETE /documentosReceptor/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DocumentoReceptor $documentoReceptor */
        $documentoReceptor = $this->documentoReceptorRepository->findWithoutFail($id);

        if (empty($documentoReceptor)) {
            return $this->sendError('Documento Receptor not found');
        }

        $documentoReceptor->delete();

        return $this->sendResponse($id, 'Documento Receptor deleted successfully');
    }
}
