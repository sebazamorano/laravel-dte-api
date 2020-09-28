<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\DocumentoAutorizado;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Repositories\DocumentoAutorizadoRepository;
use App\Http\Requests\API\CreateDocumentoAutorizadoAPIRequest;
use App\Http\Requests\API\UpdateDocumentoAutorizadoAPIRequest;

/**
 * Class DocumentoAutorizadoController.
 */
class DocumentoAutorizadoAPIController extends AppBaseController
{
    /** @var DocumentoAutorizadoRepository */
    private $documentoAutorizadoRepository;

    public function __construct(DocumentoAutorizadoRepository $documentoAutorizadoRepo)
    {
        $this->documentoAutorizadoRepository = $documentoAutorizadoRepo;
    }

    /**
     * Display a listing of the DocumentoAutorizado.
     * GET|HEAD /documentosAutorizados.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->documentoAutorizadoRepository->pushCriteria(new RequestCriteria($request));
        $this->documentoAutorizadoRepository->pushCriteria(new LimitOffsetCriteria($request));
        $documentosAutorizados = $this->documentoAutorizadoRepository->all();

        return $this->sendResponse($documentosAutorizados->toArray(), 'Documentos Autorizados retrieved successfully');
    }

    /**
     * Store a newly created DocumentoAutorizado in storage.
     * POST /documentosAutorizados.
     *
     * @param CreateDocumentoAutorizadoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoAutorizadoAPIRequest $request)
    {
        $input = $request->all();

        $documentosAutorizados = $this->documentoAutorizadoRepository->create($input);

        return $this->sendResponse($documentosAutorizados->toArray(), 'Documento Autorizado saved successfully');
    }

    /**
     * Display the specified DocumentoAutorizado.
     * GET|HEAD /documentosAutorizados/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DocumentoAutorizado $documentoAutorizado */
        $documentoAutorizado = $this->documentoAutorizadoRepository->findWithoutFail($id);

        if (empty($documentoAutorizado)) {
            return $this->sendError('Documento Autorizado not found');
        }

        return $this->sendResponse($documentoAutorizado->toArray(), 'Documento Autorizado retrieved successfully');
    }

    /**
     * Update the specified DocumentoAutorizado in storage.
     * PUT/PATCH /documentosAutorizados/{id}.
     *
     * @param  int $id
     * @param UpdateDocumentoAutorizadoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoAutorizadoAPIRequest $request)
    {
        $input = $request->all();

        /** @var DocumentoAutorizado $documentoAutorizado */
        $documentoAutorizado = $this->documentoAutorizadoRepository->findWithoutFail($id);

        if (empty($documentoAutorizado)) {
            return $this->sendError('Documento Autorizado not found');
        }

        $documentoAutorizado = $this->documentoAutorizadoRepository->update($input, $id);

        return $this->sendResponse($documentoAutorizado->toArray(), 'DocumentoAutorizado updated successfully');
    }

    /**
     * Remove the specified DocumentoAutorizado from storage.
     * DELETE /documentosAutorizados/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DocumentoAutorizado $documentoAutorizado */
        $documentoAutorizado = $this->documentoAutorizadoRepository->findWithoutFail($id);

        if (empty($documentoAutorizado)) {
            return $this->sendError('Documento Autorizado not found');
        }

        $documentoAutorizado->delete();

        return $this->sendResponse($id, 'Documento Autorizado deleted successfully');
    }
}
