<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\DetalleRespuestaDteXml;
use App\Http\Controllers\AppBaseController;
use App\Repositories\DetalleRespuestaDteXmlRepository;
use App\Http\Resources\DetalleRespuestaDteXmlCollection;
use App\Http\Requests\API\CreateDetalleRespuestaDteXmlAPIRequest;
use App\Http\Requests\API\IndexDetalleRespuestaDteXmlAPIRequests;
use App\Http\Requests\API\UpdateDetalleRespuestaDteXmlAPIRequest;

/**
 * Class DetalleRespuestaDteXmlController.
 */
class DetalleRespuestaDteXmlAPIController extends AppBaseController
{
    /** @var DetalleRespuestaDteXmlRepository */
    private $detalleRespuestaDteXmlRepository;

    public function __construct(DetalleRespuestaDteXmlRepository $detalleRespuestaDteXmlRepo)
    {
        $this->detalleRespuestaDteXmlRepository = $detalleRespuestaDteXmlRepo;
    }

    /**
     * Display a listing of the DetalleRespuestaDteXml.
     * GET|HEAD /detalleRespuestaDteXmls.
     *
     * @param Request $request
     * @return Response
     */
    public function index(IndexDetalleRespuestaDteXmlAPIRequests $request)
    {
        $detalle = DetalleRespuestaDteXml::buscar($request);

        if ($request->filled('paginate')) {
            $detalle = $detalle->paginate($request->input('paginate'));
        } else {
            $detalle = $detalle->paginate(10);
        }

        return $this->sendResponse(new DetalleRespuestaDteXmlCollection($detalle), 'Detalle Respuesta Dte Xmls retrieved successfully');
        //return $this->sendResponse(new DocumentoCollection($documentos), 'Documentos retrieved successfully');
    }

    /**
     * Store a newly created DetalleRespuestaDteXml in storage.
     * POST /detalleRespuestaDteXmls.
     *
     * @param CreateDetalleRespuestaDteXmlAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDetalleRespuestaDteXmlAPIRequest $request)
    {
        $input = $request->all();

        $detalleRespuestaDteXmls = $this->detalleRespuestaDteXmlRepository->create($input);

        return $this->sendResponse($detalleRespuestaDteXmls->toArray(), 'Detalle Respuesta Dte Xml saved successfully');
    }

    /**
     * Display the specified DetalleRespuestaDteXml.
     * GET|HEAD /detalleRespuestaDteXmls/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DetalleRespuestaDteXml $detalleRespuestaDteXml */
        $detalleRespuestaDteXml = $this->detalleRespuestaDteXmlRepository->findWithoutFail($id);

        if (empty($detalleRespuestaDteXml)) {
            return $this->sendError('Detalle Respuesta Dte Xml not found');
        }

        return $this->sendResponse($detalleRespuestaDteXml->toArray(), 'Detalle Respuesta Dte Xml retrieved successfully');
    }

    /**
     * Update the specified DetalleRespuestaDteXml in storage.
     * PUT/PATCH /detalleRespuestaDteXmls/{id}.
     *
     * @param  int $id
     * @param UpdateDetalleRespuestaDteXmlAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDetalleRespuestaDteXmlAPIRequest $request)
    {
        $input = $request->all();

        /** @var DetalleRespuestaDteXml $detalleRespuestaDteXml */
        $detalleRespuestaDteXml = $this->detalleRespuestaDteXmlRepository->findWithoutFail($id);

        if (empty($detalleRespuestaDteXml)) {
            return $this->sendError('Detalle Respuesta Dte Xml not found');
        }

        $detalleRespuestaDteXml = $this->detalleRespuestaDteXmlRepository->update($input, $id);

        return $this->sendResponse($detalleRespuestaDteXml->toArray(), 'DetalleRespuestaDteXml updated successfully');
    }

    /**
     * Remove the specified DetalleRespuestaDteXml from storage.
     * DELETE /detalleRespuestaDteXmls/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DetalleRespuestaDteXml $detalleRespuestaDteXml */
        $detalleRespuestaDteXml = $this->detalleRespuestaDteXmlRepository->findWithoutFail($id);

        if (empty($detalleRespuestaDteXml)) {
            return $this->sendError('Detalle Respuesta Dte Xml not found');
        }

        $detalleRespuestaDteXml->delete();

        return $this->sendResponse($id, 'Detalle Respuesta Dte Xml deleted successfully');
    }
}
