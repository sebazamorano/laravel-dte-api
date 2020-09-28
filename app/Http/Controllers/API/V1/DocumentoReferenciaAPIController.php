<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\DocumentoReferencia;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Repositories\DocumentoReferenciaRepository;
use App\Http\Requests\API\CreateDocumentoReferenciaAPIRequest;
use App\Http\Requests\API\UpdateDocumentoReferenciaAPIRequest;

/**
 * Class DocumentoReferenciaController.
 */
class DocumentoReferenciaAPIController extends AppBaseController
{
    /** @var DocumentoReferenciaRepository */
    private $documentoReferenciaRepository;

    public function __construct(DocumentoReferenciaRepository $documentoReferenciaRepo)
    {
        $this->documentoReferenciaRepository = $documentoReferenciaRepo;
    }

    /**
     * Display a listing of the DocumentoReferencia.
     * GET|HEAD /documentosReferencias.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->documentoReferenciaRepository->pushCriteria(new RequestCriteria($request));
        $this->documentoReferenciaRepository->pushCriteria(new LimitOffsetCriteria($request));
        $documentosReferencias = $this->documentoReferenciaRepository->all();

        return $this->sendResponse($documentosReferencias->toArray(), 'Documentos Referencias retrieved successfully');
    }

    /**
     * Store a newly created DocumentoReferencia in storage.
     * POST /documentosReferencias.
     *
     * @param CreateDocumentoReferenciaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoReferenciaAPIRequest $request)
    {
        $input = $request->all();

        $documentosReferencias = $this->documentoReferenciaRepository->create($input);

        return $this->sendResponse($documentosReferencias->toArray(), 'Documento Referencia saved successfully');
    }

    /**
     * Display the specified DocumentoReferencia.
     * GET|HEAD /documentosReferencias/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DocumentoReferencia $documentoReferencia */
        $documentoReferencia = $this->documentoReferenciaRepository->findWithoutFail($id);

        if (empty($documentoReferencia)) {
            return $this->sendError('Documento Referencia not found');
        }

        return $this->sendResponse($documentoReferencia->toArray(), 'Documento Referencia retrieved successfully');
    }

    /**
     * Update the specified DocumentoReferencia in storage.
     * PUT/PATCH /documentosReferencias/{id}.
     *
     * @param  int $id
     * @param UpdateDocumentoReferenciaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoReferenciaAPIRequest $request)
    {
        $input = $request->all();

        /** @var DocumentoReferencia $documentoReferencia */
        $documentoReferencia = $this->documentoReferenciaRepository->findWithoutFail($id);

        if (empty($documentoReferencia)) {
            return $this->sendError('Documento Referencia not found');
        }

        $documentoReferencia = $this->documentoReferenciaRepository->update($input, $id);

        return $this->sendResponse($documentoReferencia->toArray(), 'DocumentoReferencia updated successfully');
    }

    /**
     * Remove the specified DocumentoReferencia from storage.
     * DELETE /documentosReferencias/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DocumentoReferencia $documentoReferencia */
        $documentoReferencia = $this->documentoReferenciaRepository->findWithoutFail($id);

        if (empty($documentoReferencia)) {
            return $this->sendError('Documento Referencia not found');
        }

        $documentoReferencia->delete();

        return $this->sendResponse($id, 'Documento Referencia deleted successfully');
    }
}
