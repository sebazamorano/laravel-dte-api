<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\DocumentoDetalle;
use App\Http\Controllers\AppBaseController;
use App\Repositories\DocumentoDetalleRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Request\API\CreateDocumentoDetalleAPIRequest;
use App\Http\Request\API\UpdateDocumentoDetalleAPIRequest;

/**
 * Class DocumentoDetalleController.
 */
class DocumentoDetalleAPIController extends AppBaseController
{
    /** @var DocumentoDetalleRepository */
    private $documentoDetalleRepository;

    public function __construct(DocumentoDetalleRepository $documentoDetalleRepo)
    {
        $this->documentoDetalleRepository = $documentoDetalleRepo;
    }

    /**
     * Display a listing of the DocumentoDetalle.
     * GET|HEAD /documentosDetalles.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->documentoDetalleRepository->pushCriteria(new RequestCriteria($request));
        $this->documentoDetalleRepository->pushCriteria(new LimitOffsetCriteria($request));
        $documentosDetalles = $this->documentoDetalleRepository->all();

        return $this->sendResponse($documentosDetalles->toArray(), 'Documentos Detalles retrieved successfully');
    }

    /**
     * Store a newly created DocumentoDetalle in storage.
     * POST /documentosDetalles.
     *
     * @param CreateDocumentoDetalleAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoDetalleAPIRequest $request)
    {
        $input = $request->all();

        $documentosDetalles = $this->documentoDetalleRepository->create($input);

        return $this->sendResponse($documentosDetalles->toArray(), 'Documento Detalle saved successfully');
    }

    /**
     * Display the specified DocumentoDetalle.
     * GET|HEAD /documentosDetalles/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DocumentoDetalle $documentoDetalle */
        $documentoDetalle = $this->documentoDetalleRepository->findWithoutFail($id);

        if (empty($documentoDetalle)) {
            return $this->sendError('Documento Detalle not found');
        }

        return $this->sendResponse($documentoDetalle->toArray(), 'Documento Detalle retrieved successfully');
    }

    /**
     * Update the specified DocumentoDetalle in storage.
     * PUT/PATCH /documentosDetalles/{id}.
     *
     * @param  int $id
     * @param UpdateDocumentoDetalleAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoDetalleAPIRequest $request)
    {
        $input = $request->all();

        /** @var DocumentoDetalle $documentoDetalle */
        $documentoDetalle = $this->documentoDetalleRepository->findWithoutFail($id);

        if (empty($documentoDetalle)) {
            return $this->sendError('Documento Detalle not found');
        }

        $documentoDetalle = $this->documentoDetalleRepository->update($input, $id);

        return $this->sendResponse($documentoDetalle->toArray(), 'DocumentoDetalle updated successfully');
    }

    /**
     * Remove the specified DocumentoDetalle from storage.
     * DELETE /documentosDetalles/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DocumentoDetalle $documentoDetalle */
        $documentoDetalle = $this->documentoDetalleRepository->findWithoutFail($id);

        if (empty($documentoDetalle)) {
            return $this->sendError('Documento Detalle not found');
        }

        $documentoDetalle->delete();

        return $this->sendResponse($id, 'Documento Detalle deleted successfully');
    }
}
