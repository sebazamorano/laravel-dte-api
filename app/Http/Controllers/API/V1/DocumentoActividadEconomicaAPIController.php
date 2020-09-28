<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\DocumentoActividadEconomica;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Repositories\DocumentoActividadEconomicaRepository;
use App\Http\Requests\API\CreateDocumentoActividadEconomicaAPIRequest;
use App\Http\Requests\API\UpdateDocumentoActividadEconomicaAPIRequest;

/**
 * Class DocumentoActividadEconomicaController.
 */
class DocumentoActividadEconomicaAPIController extends AppBaseController
{
    /** @var DocumentoActividadEconomicaRepository */
    private $documentoActividadEconomicaRepository;

    public function __construct(DocumentoActividadEconomicaRepository $documentoActividadEconomicaRepo)
    {
        $this->documentoActividadEconomicaRepository = $documentoActividadEconomicaRepo;
    }

    /**
     * Display a listing of the DocumentoActividadEconomica.
     * GET|HEAD /documentosActividadesEconomicas.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->documentoActividadEconomicaRepository->pushCriteria(new RequestCriteria($request));
        $this->documentoActividadEconomicaRepository->pushCriteria(new LimitOffsetCriteria($request));
        $documentosActividadesEconomicas = $this->documentoActividadEconomicaRepository->all();

        return $this->sendResponse($documentosActividadesEconomicas->toArray(), 'Documentos Actividades Economicas retrieved successfully');
    }

    /**
     * Store a newly created DocumentoActividadEconomica in storage.
     * POST /documentosActividadesEconomicas.
     *
     * @param CreateDocumentoActividadEconomicaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoActividadEconomicaAPIRequest $request)
    {
        $input = $request->all();

        $documentosActividadesEconomicas = $this->documentoActividadEconomicaRepository->create($input);

        return $this->sendResponse($documentosActividadesEconomicas->toArray(), 'Documento Actividad Economica saved successfully');
    }

    /**
     * Display the specified DocumentoActividadEconomica.
     * GET|HEAD /documentosActividadesEconomicas/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DocumentoActividadEconomica $documentoActividadEconomica */
        $documentoActividadEconomica = $this->documentoActividadEconomicaRepository->findWithoutFail($id);

        if (empty($documentoActividadEconomica)) {
            return $this->sendError('Documento Actividad Economica not found');
        }

        return $this->sendResponse($documentoActividadEconomica->toArray(), 'Documento Actividad Economica retrieved successfully');
    }

    /**
     * Update the specified DocumentoActividadEconomica in storage.
     * PUT/PATCH /documentosActividadesEconomicas/{id}.
     *
     * @param  int $id
     * @param UpdateDocumentoActividadEconomicaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoActividadEconomicaAPIRequest $request)
    {
        $input = $request->all();

        /** @var DocumentoActividadEconomica $documentoActividadEconomica */
        $documentoActividadEconomica = $this->documentoActividadEconomicaRepository->findWithoutFail($id);

        if (empty($documentoActividadEconomica)) {
            return $this->sendError('Documento Actividad Economica not found');
        }

        $documentoActividadEconomica = $this->documentoActividadEconomicaRepository->update($input, $id);

        return $this->sendResponse($documentoActividadEconomica->toArray(), 'DocumentoActividadEconomica updated successfully');
    }

    /**
     * Remove the specified DocumentoActividadEconomica from storage.
     * DELETE /documentosActividadesEconomicas/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DocumentoActividadEconomica $documentoActividadEconomica */
        $documentoActividadEconomica = $this->documentoActividadEconomicaRepository->findWithoutFail($id);

        if (empty($documentoActividadEconomica)) {
            return $this->sendError('Documento Actividad Economica not found');
        }

        $documentoActividadEconomica->delete();

        return $this->sendResponse($id, 'Documento Actividad Economica deleted successfully');
    }
}
