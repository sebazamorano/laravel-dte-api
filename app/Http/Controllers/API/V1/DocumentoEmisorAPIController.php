<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\DocumentoEmisor;
use App\Http\Controllers\AppBaseController;
use App\Repositories\DocumentoEmisorRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Requests\API\CreateDocumentoEmisorAPIRequest;
use App\Http\Requests\API\UpdateDocumentoEmisorAPIRequest;

/**
 * Class DocumentoEmisorController.
 */
class DocumentoEmisorAPIController extends AppBaseController
{
    /** @var DocumentoEmisorRepository */
    private $documentoEmisorRepository;

    public function __construct(DocumentoEmisorRepository $documentoEmisorRepo)
    {
        $this->documentoEmisorRepository = $documentoEmisorRepo;
    }

    /**
     * Display a listing of the DocumentoEmisor.
     * GET|HEAD /documentosEmisor.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->documentoEmisorRepository->pushCriteria(new RequestCriteria($request));
        $this->documentoEmisorRepository->pushCriteria(new LimitOffsetCriteria($request));
        $documentosEmisor = $this->documentoEmisorRepository->all();

        return $this->sendResponse($documentosEmisor->toArray(), 'Documentos Emisor retrieved successfully');
    }

    /**
     * Store a newly created DocumentoEmisor in storage.
     * POST /documentosEmisor.
     *
     * @param CreateDocumentoEmisorAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoEmisorAPIRequest $request)
    {
        $input = $request->all();

        $documentosEmisor = $this->documentoEmisorRepository->create($input);

        return $this->sendResponse($documentosEmisor->toArray(), 'Documento Emisor saved successfully');
    }

    /**
     * Display the specified DocumentoEmisor.
     * GET|HEAD /documentosEmisor/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DocumentoEmisor $documentoEmisor */
        $documentoEmisor = $this->documentoEmisorRepository->findWithoutFail($id);

        if (empty($documentoEmisor)) {
            return $this->sendError('Documento Emisor not found');
        }

        return $this->sendResponse($documentoEmisor->toArray(), 'Documento Emisor retrieved successfully');
    }

    /**
     * Update the specified DocumentoEmisor in storage.
     * PUT/PATCH /documentosEmisor/{id}.
     *
     * @param  int $id
     * @param UpdateDocumentoEmisorAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoEmisorAPIRequest $request)
    {
        $input = $request->all();

        /** @var DocumentoEmisor $documentoEmisor */
        $documentoEmisor = $this->documentoEmisorRepository->findWithoutFail($id);

        if (empty($documentoEmisor)) {
            return $this->sendError('Documento Emisor not found');
        }

        $documentoEmisor = $this->documentoEmisorRepository->update($input, $id);

        return $this->sendResponse($documentoEmisor->toArray(), 'DocumentoEmisor updated successfully');
    }

    /**
     * Remove the specified DocumentoEmisor from storage.
     * DELETE /documentosEmisor/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DocumentoEmisor $documentoEmisor */
        $documentoEmisor = $this->documentoEmisorRepository->findWithoutFail($id);

        if (empty($documentoEmisor)) {
            return $this->sendError('Documento Emisor not found');
        }

        $documentoEmisor->delete();

        return $this->sendResponse($id, 'Documento Emisor deleted successfully');
    }
}
