<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\DocumentoTransporte;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Repositories\DocumentoTransporteRepository;
use App\Http\Request\API\CreateDocumentoTransporteAPIRequest;
use App\Http\Request\API\UpdateDocumentoTransporteAPIRequest;

/**
 * Class DocumentoTransporteController.
 */
class DocumentoTransporteAPIController extends AppBaseController
{
    /** @var DocumentoTransporteRepository */
    private $documentoTransporteRepository;

    public function __construct(DocumentoTransporteRepository $documentoTransporteRepo)
    {
        $this->documentoTransporteRepository = $documentoTransporteRepo;
    }

    /**
     * Display a listing of the DocumentoTransporte.
     * GET|HEAD /documentoTransportes.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->documentoTransporteRepository->pushCriteria(new RequestCriteria($request));
        $this->documentoTransporteRepository->pushCriteria(new LimitOffsetCriteria($request));
        $documentoTransportes = $this->documentoTransporteRepository->all();

        return $this->sendResponse($documentoTransportes->toArray(), 'Documento Transportes retrieved successfully');
    }

    /**
     * Store a newly created DocumentoTransporte in storage.
     * POST /documentoTransportes.
     *
     * @param CreateDocumentoTransporteAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoTransporteAPIRequest $request)
    {
        $input = $request->all();

        $documentoTransportes = $this->documentoTransporteRepository->create($input);

        return $this->sendResponse($documentoTransportes->toArray(), 'Documento Transporte saved successfully');
    }

    /**
     * Display the specified DocumentoTransporte.
     * GET|HEAD /documentoTransportes/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DocumentoTransporte $documentoTransporte */
        $documentoTransporte = $this->documentoTransporteRepository->findWithoutFail($id);

        if (empty($documentoTransporte)) {
            return $this->sendError('Documento Transporte not found');
        }

        return $this->sendResponse($documentoTransporte->toArray(), 'Documento Transporte retrieved successfully');
    }

    /**
     * Update the specified DocumentoTransporte in storage.
     * PUT/PATCH /documentoTransportes/{id}.
     *
     * @param  int $id
     * @param UpdateDocumentoTransporteAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoTransporteAPIRequest $request)
    {
        $input = $request->all();

        /** @var DocumentoTransporte $documentoTransporte */
        $documentoTransporte = $this->documentoTransporteRepository->findWithoutFail($id);

        if (empty($documentoTransporte)) {
            return $this->sendError('Documento Transporte not found');
        }

        $documentoTransporte = $this->documentoTransporteRepository->update($input, $id);

        return $this->sendResponse($documentoTransporte->toArray(), 'DocumentoTransporte updated successfully');
    }

    /**
     * Remove the specified DocumentoTransporte from storage.
     * DELETE /documentoTransportes/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DocumentoTransporte $documentoTransporte */
        $documentoTransporte = $this->documentoTransporteRepository->findWithoutFail($id);

        if (empty($documentoTransporte)) {
            return $this->sendError('Documento Transporte not found');
        }

        $documentoTransporte->delete();

        return $this->sendResponse($id, 'Documento Transporte deleted successfully');
    }
}
