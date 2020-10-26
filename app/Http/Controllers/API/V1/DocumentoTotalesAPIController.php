<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\DocumentoTotales;
use App\Http\Controllers\AppBaseController;
use App\Repositories\DocumentoTotalesRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Request\API\CreateDocumentoTotalesAPIRequest;
use App\Http\Request\API\UpdateDocumentoTotalesAPIRequest;

/**
 * Class DocumentoTotalesController.
 */
class DocumentoTotalesAPIController extends AppBaseController
{
    /** @var DocumentoTotalesRepository */
    private $documentoTotalesRepository;

    public function __construct(DocumentoTotalesRepository $documentoTotalesRepo)
    {
        $this->documentoTotalesRepository = $documentoTotalesRepo;
    }

    /**
     * Display a listing of the DocumentoTotales.
     * GET|HEAD /documentosTotales.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->documentoTotalesRepository->pushCriteria(new RequestCriteria($request));
        $this->documentoTotalesRepository->pushCriteria(new LimitOffsetCriteria($request));
        $documentosTotales = $this->documentoTotalesRepository->all();

        return $this->sendResponse($documentosTotales->toArray(), 'Documentos Totales retrieved successfully');
    }

    /**
     * Store a newly created DocumentoTotales in storage.
     * POST /documentosTotales.
     *
     * @param CreateDocumentoTotalesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoTotalesAPIRequest $request)
    {
        $input = $request->all();

        $documentosTotales = $this->documentoTotalesRepository->create($input);

        return $this->sendResponse($documentosTotales->toArray(), 'Documento Totales saved successfully');
    }

    /**
     * Display the specified DocumentoTotales.
     * GET|HEAD /documentosTotales/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DocumentoTotales $documentoTotales */
        $documentoTotales = $this->documentoTotalesRepository->findWithoutFail($id);

        if (empty($documentoTotales)) {
            return $this->sendError('Documento Totales not found');
        }

        return $this->sendResponse($documentoTotales->toArray(), 'Documento Totales retrieved successfully');
    }

    /**
     * Update the specified DocumentoTotales in storage.
     * PUT/PATCH /documentosTotales/{id}.
     *
     * @param  int $id
     * @param UpdateDocumentoTotalesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoTotalesAPIRequest $request)
    {
        $input = $request->all();

        /** @var DocumentoTotales $documentoTotales */
        $documentoTotales = $this->documentoTotalesRepository->findWithoutFail($id);

        if (empty($documentoTotales)) {
            return $this->sendError('Documento Totales not found');
        }

        $documentoTotales = $this->documentoTotalesRepository->update($input, $id);

        return $this->sendResponse($documentoTotales->toArray(), 'DocumentoTotales updated successfully');
    }

    /**
     * Remove the specified DocumentoTotales from storage.
     * DELETE /documentosTotales/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DocumentoTotales $documentoTotales */
        $documentoTotales = $this->documentoTotalesRepository->findWithoutFail($id);

        if (empty($documentoTotales)) {
            return $this->sendError('Documento Totales not found');
        }

        $documentoTotales->delete();

        return $this->sendResponse($id, 'Documento Totales deleted successfully');
    }
}
